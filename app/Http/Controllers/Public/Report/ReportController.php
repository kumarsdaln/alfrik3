<?php

namespace App\Http\Controllers\Public\Report;

use App\Enums\Report\ReportStatus;
use App\Enums\Report\ReportType;
use App\Http\Controllers\Controller;
use App\Http\Resources\Report\ReportResource;
use App\Models\Report\Report;
use App\Support\Breadcrumbs\BreadcrumbBuilder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ReportController extends Controller
{
    /**
     * Display published reports.
     */
    public function index(Request $request): Response
    {
        $search = trim(
            (string) $request->query('search', '')
        );

        $activeType = trim(
            (string) $request->query('type', '')
        );

        /*
        |--------------------------------------------------------------------------
        | Reports
        |--------------------------------------------------------------------------
        */

        $query = Report::query()
            ->where(
                'status',
                ReportStatus::Published
            )
            ->with([
                'research:id,title',
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
                        );
                })
            )
            ->orderByDesc('published_at')
            ->orderByDesc('created_at');

        /*
        |--------------------------------------------------------------------------
        | Featured Report
        |--------------------------------------------------------------------------
        |
        | Only show the featured report when the user is browsing the
        | main reports page without search or type filters.
        |
        */

        $featured = null;

        if ($activeType === '' && $search === '') {
            $featured = Report::query()
                ->where(
                    'status',
                    ReportStatus::Published
                )
                ->where('featured', true)
                ->with([
                    'research:id,title',
                    'author:id,name',
                    'media',
                ])
                ->orderByDesc('published_at')
                ->orderByDesc('created_at')
                ->first();

            /*
            |--------------------------------------------------------------------------
            | Fallback to latest published report
            |--------------------------------------------------------------------------
            */

            if (! $featured) {
                $featured = Report::query()
                    ->where(
                        'status',
                        ReportStatus::Published
                    )
                    ->with([
                        'research:id,title',
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

        return Inertia::render('reports/Index', [
            'reports' => Inertia::scroll(
                fn () => ReportResource::collection(
                    $query
                        ->paginate(12)
                        ->withQueryString()
                )
            ),

            'featured' => $featured
                ? new ReportResource($featured)
                : null,

            /*
            |--------------------------------------------------------------------------
            | Report Types
            |--------------------------------------------------------------------------
            */

            'types' => ReportType::dropdown(),

            'qfilters' => [
                'type' => $activeType,
                'search' => $search,
            ],

            'breadcrumbs' => BreadcrumbBuilder::make()
                ->home()
                ->add('Reports')
                ->toArray(),
        ]);
    }

    /**
     * Display a single published report.
     */
    public function show(Report $report): Response
    {
        abort_unless(
            $this->isLive($report),
            404
        );

        /*
        |--------------------------------------------------------------------------
        | Report
        |--------------------------------------------------------------------------
        */

        $report->load([
            'research:id,title',
            'author:id,name',
            'media',
            'seo',

            'sections' => fn ($query) => $query
                ->orderBy('position'),

            'sections.contentBlocks' => fn ($query) => $query
                ->orderBy('position'),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Related Reports
        |--------------------------------------------------------------------------
        */

        $related = Report::query()
            ->where(
                'status',
                ReportStatus::Published
            )
            ->where(
                'id',
                '!=',
                $report->id
            )
            ->when(
                $report->type,
                fn ($query) => $query->where(
                    'type',
                    $report->type->value
                )
            )
            ->with([
                'research:id,title',
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

        return Inertia::render('reports/Show', [
            'report' => new ReportResource($report),

            'related' => ReportResource::collection(
                $related
            ),

            'breadcrumbs' => BreadcrumbBuilder::make()
                ->home()
                ->add(
                    'Reports',
                    route('reports.index')
                )
                ->add($report->title)
                ->toArray(),

            /*
            |--------------------------------------------------------------------------
            | SEO
            |--------------------------------------------------------------------------
            */

            'meta_data' => [
                'meta_title' => $report->seo?->title
                    ?: $report->title,

                'meta_description' => $report->seo?->description
                    ?: $report->summary,

                'canonical_url' => $report->seo?->canonical_url,

                'og_title' => $report->seo?->og_title,
                'og_description' => $report->seo?->og_description,
                'og_image' => $report->seo?->og_image,

                'twitter_title' => $report->seo?->twitter_title,
                'twitter_description' => $report->seo?->twitter_description,
                'twitter_image' => $report->seo?->twitter_image,
            ],
        ]);
    }

    /**
     * Determine whether a report is publicly available.
     */
    private function isLive(Report $report): bool
    {
        return $report->status === ReportStatus::Published
            && (
                is_null($report->published_at)
                || $report->published_at->lte(now())
            );
    }
}