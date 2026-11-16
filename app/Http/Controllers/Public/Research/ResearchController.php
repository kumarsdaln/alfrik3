<?php

namespace App\Http\Controllers\Public\Research;

use App\Enums\Research\ResearchStatus;
use App\Enums\Research\ResearchType;
use App\Http\Controllers\Controller;
use App\Http\Resources\Research\ResearchResource;
use App\Http\Resources\SeoMetadataResource;
use App\Models\Research\Research;
use App\Support\Breadcrumbs\BreadcrumbBuilder;
use Illuminate\Database\Eloquent\Builder;
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
        $activeType = trim((string) $request->query('type', ''));
        $search = trim((string) $request->query('search', ''));

        /*
        |--------------------------------------------------------------------------
        | Base Research Query
        |--------------------------------------------------------------------------
        */

        $query = Research::query()
            ->where('status', ResearchStatus::Published)
            ->where(function (Builder $query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->with($this->researchRelations())
            ->when(
                $activeType !== '',
                fn(Builder $query) => $query->where('type', $activeType)
            )
            ->when(
                $search !== '',
                fn(Builder $query) => $query->where(function (Builder $query) use ($search) {
                    $query
                        ->where('title', 'ilike', "%{$search}%")
                        ->orWhere('subtitle', 'ilike', "%{$search}%")
                        ->orWhere('summary', 'ilike', "%{$search}%")
                        ->orWhere('description', 'ilike', "%{$search}%");
                })
            )
            ->orderByDesc('published_at')
            ->orderByDesc('created_at');

        /*
        |--------------------------------------------------------------------------
        | Featured Research
        |--------------------------------------------------------------------------
        */

        $featured = null;

        if ($activeType === '' && $search === '') {
            $featured = Research::query()
                ->where('status', ResearchStatus::Published)
                ->where('featured', true)
                ->where(function (Builder $query) {
                    $query->whereNull('published_at')
                        ->orWhere('published_at', '<=', now());
                })
                ->with($this->researchRelations())
                ->orderByDesc('published_at')
                ->orderByDesc('created_at')
                ->first();

            // Fallback to latest published research.
            $featured ??= Research::query()
                ->where('status', ResearchStatus::Published)
                ->where(function (Builder $query) {
                    $query->whereNull('published_at')
                        ->orWhere('published_at', '<=', now());
                })
                ->with($this->researchRelations())
                ->orderByDesc('published_at')
                ->orderByDesc('created_at')
                ->first();
        }

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return Inertia::render('research/Index', [
            'researches' => Inertia::scroll(
                fn() => ResearchResource::collection(
                    $query->paginate(12)->withQueryString()
                )
            ),

            'featured' => $featured
                ? new ResearchResource($featured)
                : null,

            'typeOptions' => ResearchType::dropdown(),

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
        abort_unless($this->isLive($research), 404);

        /*
    |--------------------------------------------------------------------------
    | Research Details
    |--------------------------------------------------------------------------
    */

        $research->load([
            ...$this->researchRelations(),

            'methodology',

            'sources' => fn($query) => $query->orderBy('position'),

            'questions' => fn($query) => $query->orderBy('position'),

            'findings' => fn($query) => $query->orderBy('position'),

            'evidence' => fn($query) => $query->orderBy('position'),
        ]);

        /*
    |--------------------------------------------------------------------------
    | Related Research
    |--------------------------------------------------------------------------
    */

        $categoryIds = $research->categories->pluck('id');
        $tagIds = $research->tags->pluck('id');

        $related = Research::query()
            ->where('status', ResearchStatus::Published)
            ->where(function (Builder $query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->where('id', '!=', $research->getKey())
            ->where(function (Builder $query) use (
                $categoryIds,
                $tagIds,
                $research
            ) {
                $hasTaxonomy = false;

                if ($categoryIds->isNotEmpty()) {
                    $query->whereHas(
                        'categories',
                        fn($relation) => $relation->whereIn(
                            'categories.id',
                            $categoryIds
                        )
                    );

                    $hasTaxonomy = true;
                }

                if ($tagIds->isNotEmpty()) {
                    $method = $hasTaxonomy
                        ? 'orWhereHas'
                        : 'whereHas';

                    $query->{$method}(
                        'tags',
                        fn($relation) => $relation->whereIn(
                            'tags.id',
                            $tagIds
                        )
                    );

                    $hasTaxonomy = true;
                }

                if (! $hasTaxonomy) {
                    $query->where('type', $research->type);
                }
            })
            ->with($this->researchRelations())
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

            'related' => ResearchResource::collection($related),

            'breadcrumbs' => BreadcrumbBuilder::make()
                ->home()
                ->add('Research', route('research.index'))
                ->add($research->title)
                ->toArray(),

            'meta_data' => $research->seo
                ? new SeoMetadataResource($research->seo)
                : null,
        ]);
    }


    /**
     * Shared relationships used by public research listings.
     */

    private function researchRelations(): array
    {
        return [
            'participants' => fn($query) => $query
                ->with([
                    'user:id,name,username,avatar',
                    'role:id,name,slug',
                ])
                ->orderBy('position'),

            'media',
            'categories',
            'tags',
            'seo',
        ];
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
