<?php

namespace App\Http\Controllers\Public\Research;

use App\Enums\Research\ResearchStatus;
use App\Enums\Research\ResearchType;
use App\Http\Controllers\Controller;
use App\Http\Resources\Research\ResearchResource;
use App\Http\Resources\SeoMetadataResource;
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
                fn($query) => $query->where(
                    'type',
                    $activeType
                )
            )
            ->when(
                $search !== '',
                fn($query) => $query->where(function ($query) use ($search) {
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
        | Only show featured research when the user is browsing
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
            | Fallback to Latest Published Research
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
            'researches' => Inertia::scroll(
                fn() => ResearchResource::collection(
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

            'typeOptions' => ResearchType::dropdown(),

            /*
            |--------------------------------------------------------------------------
            | Filters
            |--------------------------------------------------------------------------
            */

            'filters' => [
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
            'sources' => fn($query) => $query
                ->orderBy('position'),
            'questions' => fn($query) => $query
                ->orderBy('position'),
            'findings' => fn($query) => $query
                ->orderBy('position'),
            'evidence' => fn($query) => $query
                ->orderBy('position'),
            'members',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Related Research
        |--------------------------------------------------------------------------
        |
        | Related research is based on the same research type.
        |
        */

        $related = Research::query()
            ->where('status', ResearchStatus::Published)
            ->where('id', '!=', $research->id)
            ->where(
                'type',
                $research->type instanceof ResearchType
                    ? $research->type->value
                    : $research->type
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

            'meta_data' => $research->seo ? new SeoMetadataResource($research->seo) : null,
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
