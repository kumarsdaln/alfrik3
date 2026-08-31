<?php

namespace App\Http\Controllers\Public\Report;

use App\Http\Controllers\Controller;
use App\Http\Resources\Report\ReportResource;
use App\Support\Breadcrumbs\BreadcrumbBuilder;
use App\Models\Report\Report;
use App\Models\Report\ReportCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ReportController extends Controller
{
    public function index(Request $request): Response
    {
        $activeCategory = trim((string) $request->query('category', ''));
        $search = trim((string) $request->query('search', ''));

        /*
        |--------------------------------------------------------------------------
        | Reports
        |--------------------------------------------------------------------------
        */

        $query = Report::query()
            ->published()
            ->with([
                'category:id,name,slug',
                'author:id,name,username',
            ])
            ->when(
                $activeCategory !== '',
                fn($query) => $query->whereHas(
                    'category',
                    fn($category) => $category->where(
                        'slug',
                        $activeCategory
                    )
                )
            )
            ->when(
                $search !== '',
                fn($query) => $query->where(function ($query) use ($search) {
                    $query
                        ->where('title', 'ilike', "%{$search}%")
                        ->orWhere('summary', 'ilike', "%{$search}%");
                })
            )
            ->orderByDesc('published_at')
            ->orderByDesc('created_at');

        /*
        |--------------------------------------------------------------------------
        | Featured Report
        |--------------------------------------------------------------------------
        */

        $featured = null;

        if ($activeCategory === '' && $search === '') {
            $featured = Report::query()
                ->published()
                ->featured()
                ->with([
                    'category:id,name,slug',
                    'author:id,name,username',
                ])
                ->orderByDesc('published_at')
                ->orderByDesc('created_at')
                ->first();

            // Fallback to latest published report.
            if (! $featured) {
                $featured = Report::query()
                    ->published()
                    ->with([
                        'category:id,name,slug',
                        'author:id,name,username',
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

        return Inertia::render('reports/Index', [
            'reports' => Inertia::scroll(
                fn() => ReportResource::collection(
                    $query
                        ->paginate(12)
                        ->withQueryString()
                )
            ),

            'featured' => $featured
                ? new ReportResource($featured)
                : null,

            'categories' => ReportCategory::query()
                ->withCount([
                    'reports as published_count' => fn($query) => $query->published(),
                ])
                ->orderBy('name')
                ->get([
                    'id',
                    'name',
                    'slug',
                ]),

            'qfilters' => [
                'category' => $activeCategory,
                'search' => $search,
            ],

            'breadcrumbs' => BreadcrumbBuilder::make()
                ->home()
                ->add('Reports')
                ->toArray(),
        ]);
    }

    public function show(Report $report)
    {
        abort_unless($this->isLive($report), 404);

        $report->load(['category:id,name,slug', 'author:id,name,username']);

        $related = Report::query()
            ->published()
            ->with('category:id,name,slug')
            ->where('id', '!=', $report->id)
            ->when($report->category_id, fn($q) => $q->where('category_id', $report->category_id))
            ->latest('published_at')
            ->take(3)
            ->get(['id', 'title', 'slug', 'summary', 'cover_image', 'category_id', 'published_at']);

        return Inertia::render('reports/Show', [
            'report' => $report,
            'related' => $related,
            'canDownload' => ! $report->gated || auth()->check(),
            'breadcrumbs' => BreadcrumbBuilder::make()->home()
                ->add('Reports', route('reports.index'))
                ->add($report->title)
                ->toArray(),
            'meta_data' => [
                'meta_title' => $report->meta_title,
                'meta_description' => $report->meta_description,
                'meta_keywords' => $report->meta_keywords,
            ],
        ]);
    }

    public function download(Report $report)
    {
        abort_unless($this->isLive($report), 404);
        abort_unless((bool) $report->file_path, 404, 'No file attached to this report.');

        // Gated reports require an authenticated member.
        if ($report->gated && ! auth()->check()) {
            return redirect()->guest(route('login'));
        }

        $relative = ltrim(str_replace('storage/', '', ltrim($report->file_path, '/')), '/');
        abort_unless(Storage::disk('public')->exists($relative), 404, 'File not found.');

        $report->increment('download_count');

        $downloadName = \Illuminate\Support\Str::slug($report->title) . '.' . ($report->file_type ?: 'pdf');

        return Storage::disk('public')->download($relative, $downloadName);
    }

    private function isLive(Report $report): bool
    {
        return $report->status && (is_null($report->published_at) || $report->published_at->lte(now()));
    }
}
