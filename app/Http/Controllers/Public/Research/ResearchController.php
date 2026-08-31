<?php

namespace App\Http\Controllers\Public\Research;

use App\Http\Controllers\Controller;
use App\Support\Breadcrumbs\BreadcrumbBuilder;
use App\Models\Research\ResearchArea;
use App\Models\Research\ResearchPaper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ResearchController extends Controller
{
    public function index(Request $request)
    {
        $activeArea = trim((string) $request->query('area', ''));
        $search = trim((string) $request->query('search', ''));

        $columns = [
            'id',
            'title',
            'slug',
            'abstract',
            'authors',
            'author_id',
            'area_id',
            'institution',
            'cover_image',
            'published_at',
            'featured',
            'download_count',
            'created_at',
        ];

        /*
    |--------------------------------------------------------------------------
    | Research Papers Query
    |--------------------------------------------------------------------------
    */

        $query = ResearchPaper::query()
            ->published()
            ->with([
                'area:id,name,slug',
                'author:id,name,username',
            ])
            ->when(
                $activeArea !== '',
                fn($q) => $q->whereHas(
                    'area',
                    fn($area) => $area->where('slug', $activeArea)
                )
            )
            ->when(
                $search !== '',
                fn($q) => $q->where(function ($searchQuery) use ($search) {
                    $searchQuery
                        ->where('title', 'ilike', "%{$search}%")
                        ->orWhere('abstract', 'ilike', "%{$search}%")
                        ->orWhere('authors', 'ilike', "%{$search}%")
                        ->orWhere('institution', 'ilike', "%{$search}%");
                })
            )
            ->orderByDesc('published_at')
            ->orderByDesc('created_at');


        /*
    |--------------------------------------------------------------------------
    | Featured Research Paper
    |--------------------------------------------------------------------------
    |
    | Only show the featured section when the user is browsing the main
    | research page, not when filtering/searching.
    |
    */

        $featured = null;

        if ($activeArea === '' && $search === '') {
            $featured = ResearchPaper::query()
                ->published()
                ->featured()
                ->with([
                    'area:id,name,slug',
                    'author:id,name,username',
                ])
                ->orderByDesc('published_at')
                ->orderByDesc('created_at')
                ->first($columns);

            /*
         * If there is no explicitly featured paper, use the newest
         * published paper as the featured paper.
         */
            if (! $featured) {
                $featured = (clone $query)->first($columns);
            }
        }


        /*
    |--------------------------------------------------------------------------
    | Research Areas
    |--------------------------------------------------------------------------
    */

        $areas = ResearchArea::query()
            ->withCount([
                'papers as published_count' => fn($q) => $q->published(),
            ])
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'slug',
            ]);


        /*
    |--------------------------------------------------------------------------
    | Response
    |--------------------------------------------------------------------------
    */

        return Inertia::render('research/Index', [
            'papers' => Inertia::scroll(
                fn() => $query
                    ->paginate(12, $columns)
                    ->withQueryString()
            ),

            'featured' => $featured,

            'areas' => $areas,

            'qfilters' => [
                'area' => $activeArea,
                'search' => $search,
            ],

            'breadcrumbs' => BreadcrumbBuilder::make()
                ->home()
                ->add('Research')
                ->toArray(),
        ]);
    }

    public function show(ResearchPaper $paper)
    {
        abort_unless($this->isLive($paper), 404);

        // Main paper
        $paper->load([
            'area:id,name,slug',
            'author:id,name,username',
        ]);

        // Related research
        $related = ResearchPaper::query()
            ->published()
            ->whereKeyNot($paper->id)
            ->when(
                $paper->area_id,
                fn($query) => $query->where('area_id', $paper->area_id)
            )
            ->with([
                'area:id,name,slug',
            ])
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->limit(3)
            ->get([
                'id',
                'title',
                'slug',
                'authors',
                'cover_image',
                'area_id',
                'published_at',
            ]);

        return Inertia::render('research/Show', [
            'paper' => $paper,
            'related' => $related,

            'breadcrumbs' => BreadcrumbBuilder::make()
                ->home()
                ->add('Research', route('research.index'))
                ->add($paper->title)
                ->toArray(),

            'meta_data' => [
                'meta_title' => $paper->meta_title ?: $paper->title,
                'meta_description' => $paper->meta_description ?: $paper->abstract,
                'meta_keywords' => $paper->meta_keywords ?: $paper->keywords,
            ],
        ]);
    }

    public function download(ResearchPaper $paper)
    {
        abort_unless($this->isLive($paper), 404);
        abort_unless((bool) $paper->file_path, 404, 'No file attached to this paper.');

        $relative = ltrim(str_replace('storage/', '', ltrim($paper->file_path, '/')), '/');
        abort_unless(Storage::disk('public')->exists($relative), 404, 'File not found.');

        $paper->increment('download_count');

        return Storage::disk('public')->download($relative, Str::slug($paper->title) . '.' . ($paper->file_type ?: 'pdf'));
    }

    private function isLive(ResearchPaper $paper): bool
    {
        return $paper->status && (is_null($paper->published_at) || $paper->published_at->lte(now()));
    }
}
