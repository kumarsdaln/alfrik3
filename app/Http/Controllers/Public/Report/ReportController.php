<?php

namespace App\Http\Controllers\Public\Report;

use App\Http\Controllers\Controller;
use App\Support\Breadcrumbs\BreadcrumbBuilder;
use App\Models\Report\Report;
use App\Models\Report\ReportCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $activeCategory = $request->query('category');
        $search = trim((string) $request->query('search', ''));

        $columns = [
            'id', 'title', 'slug', 'summary', 'cover_image', 'category_id', 'author_id',
            'report_year', 'published_at', 'featured', 'gated', 'download_count', 'created_at',
        ];

        $query = Report::query()
            ->published()
            ->with(['category:id,name,slug', 'author:id,name,username'])
            ->when($activeCategory, fn ($q) => $q->whereHas('category', fn ($c) => $c->where('slug', $activeCategory)))
            ->when($search !== '', fn ($q) => $q->where(function ($w) use ($search) {
                $w->where('title', 'ilike', "%{$search}%")->orWhere('summary', 'ilike', "%{$search}%");
            }))
            ->orderByDesc('published_at')
            ->orderByDesc('created_at');

        $featured = null;
        if (! $activeCategory && $search === '') {
            $featured = Report::published()
                ->featured()
                ->with(['category:id,name,slug', 'author:id,name,username'])
                ->orderByDesc('published_at')
                ->first($columns)
                ?? (clone $query)->first($columns);
        }

        return Inertia::render('Reports/Index', [
            'reports' => Inertia::scroll(fn () => $query->paginate(12, $columns)->withQueryString()),
            'featured' => $featured,
            'categories' => ReportCategory::query()
                ->withCount(['reports as published_count' => fn ($q) => $q->where('status', true)])
                ->orderBy('name')
                ->get(['id', 'name', 'slug']),
            'qfilters' => ['category' => $activeCategory, 'search' => $search],
            'breadcrumbs' => BreadcrumbBuilder::make()->home()->add('Reports')->toArray(),
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
            ->when($report->category_id, fn ($q) => $q->where('category_id', $report->category_id))
            ->latest('published_at')
            ->take(3)
            ->get(['id', 'title', 'slug', 'summary', 'cover_image', 'category_id', 'published_at']);

        return Inertia::render('Reports/Show', [
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

        $downloadName = \Illuminate\Support\Str::slug($report->title).'.'.($report->file_type ?: 'pdf');

        return Storage::disk('public')->download($relative, $downloadName);
    }

    private function isLive(Report $report): bool
    {
        return $report->status && (is_null($report->published_at) || $report->published_at->lte(now()));
    }
}
