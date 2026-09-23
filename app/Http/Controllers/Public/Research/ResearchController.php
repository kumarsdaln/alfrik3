<?php

namespace App\Http\Controllers\Public\Research;

use App\Enums\Research\ResearchStatus;
use App\Enums\Research\ResearchType;
use App\Http\Controllers\Controller;
use App\Http\Resources\Research\ResearchResource;
use App\Models\Research\Research;
use App\Support\Breadcrumbs\BreadcrumbBuilder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ResearchController extends Controller
{
    /**
     * Display published research.
     */
    public function index(Request $request): Response
    {
        $activeType = trim(
            (string) $request->query('type', '')
        );

        $search = trim(
            (string) $request->query('search', '')
        );

        /*
        |--------------------------------------------------------------------------
        | Research Query
        |--------------------------------------------------------------------------
        */

        $query = Research::query()
            ->where('status', ResearchStatus::Published)
            ->with([
                'author:id,name',
                'media',
            ])
            ->when(
                $activeType !== '',
                fn ($query) => $query->where(
                    'type',
                    $activeType
                )
            )
            ->when(
                $search !== '',
                fn ($query) => $query->where(function ($query) use ($search) {
                    $query
                        ->where(
                            'title',
                            'ilike',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'subtitle',
                            'ilike',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'summary',
                            'ilike',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'description',
                            'ilike',
                            "%{$search}%"
                        );
                })
            )
            ->orderByDesc('published_at')
            ->orderByDesc('created_at');

        /*
        |--------------------------------------------------------------------------
        | Featured Research
        |--------------------------------------------------------------------------
        |
        | Only show the featured research area when the user is browsing
        | the main research page without filters.
        |
        */

        $featured = null;

        if ($activeType === '' && $search === '') {
            $featured = Research::query()
                ->where('status', ResearchStatus::Published)
                ->where('featured', true)
                ->with([
                    'author:id,name',
                    'media',
                ])
                ->orderByDesc('published_at')
                ->orderByDesc('created_at')
                ->first();

            /*
            |--------------------------------------------------------------------------
            | Fallback to latest published research
            |--------------------------------------------------------------------------
            */

            if (! $featured) {
                $featured = Research::query()
                    ->where('status', ResearchStatus::Published)
                    ->with([
                        'author:id,name',
                        'media',
                    ])
                    ->orderByDesc('published_at')
                    ->orderByDesc('created_at')
                    ->first();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return Inertia::render('research/Index', [
            'research' => Inertia::scroll(
                fn () => ResearchResource::collection(
                    $query
                        ->paginate(12)
                        ->withQueryString()
                )
            ),

            'featured' => $featured
                ? new ResearchResource($featured)
                : null,

            /*
            |--------------------------------------------------------------------------
            | Research Types
            |--------------------------------------------------------------------------
            */

            'types' => ResearchType::dropdown(),

            'qfilters' => [
                'type' => $activeType,
                'search' => $search,
            ],

            'breadcrumbs' => BreadcrumbBuilder::make()
                ->home()
                ->add('Research')
                ->toArray(),
        ]);
    }

    /**
     * Display a single published research.
     */
    public function show(Research $research): Response
    {
        abort_unless(
            $this->isLive($research),
            404
        );

        /*
        |--------------------------------------------------------------------------
        | Research
        |--------------------------------------------------------------------------
        */

        $research->load([
            'author:id,name',

            'media',

            'methodology',

            'sources' => fn ($query) => $query
                ->orderBy('position'),

            'questions' => fn ($query) => $query
                ->orderBy('position'),

            'findings' => fn ($query) => $query
                ->orderBy('position'),

            'evidence' => fn ($query) => $query
                ->orderBy('position'),

            'members',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Related Research
        |--------------------------------------------------------------------------
        */

        $related = Research::query()
            ->where('status', ResearchStatus::Published)
            ->where('id', '!=', $research->id)
            ->when(
                $research->type,
                fn ($query) => $query->where(
                    'type',
                    $research->type->value
                )
            )
            ->with([
                'author:id,name',
                'media',
            ])
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return Inertia::render('research/Show', [
            'research' => new ResearchResource($research),

            'related' => ResearchResource::collection(
                $related
            ),

            'breadcrumbs' => BreadcrumbBuilder::make()
                ->home()
                ->add(
                    'Research',
                    route('research.index')
                )
                ->add($research->title)
                ->toArray(),

            /*
            |--------------------------------------------------------------------------
            | SEO
            |--------------------------------------------------------------------------
            */

            'meta_data' => [
                'meta_title' => $research->seo?->title
                    ?: $research->title,

                'meta_description' => $research->seo?->description
                    ?: $research->summary,

                'canonical_url' => $research->seo?->canonical_url,

                'og_title' => $research->seo?->og_title,
                'og_description' => $research->seo?->og_description,
                'og_image' => $research->seo?->og_image,

                'twitter_title' => $research->seo?->twitter_title,
                'twitter_description' => $research->seo?->twitter_description,
                'twitter_image' => $research->seo?->twitter_image,
            ],
        ]);
    }

    /**
     * Determine whether research is publicly available.
     */
    private function isLive(Research $research): bool
    {
        return $research->status === ResearchStatus::Published
            && (
                is_null($research->published_at)
                || $research->published_at->lte(now())
            );
    }
}