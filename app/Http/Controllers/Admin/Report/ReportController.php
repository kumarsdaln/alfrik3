<?php

namespace App\Http\Controllers\Admin\Report;

use App\Actions\Report\CreateReport;
use App\Actions\Report\DeleteReport;
use App\Actions\Report\UpdateReport;
use App\Enums\Report\ReportStatus;
use App\Enums\Report\ReportType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Report\StoreReportRequest;
use App\Http\Requests\Report\UpdateReportRequest;
use App\Http\Resources\Report\ReportResource;
use App\Models\Report\Report;
use App\Support\Breadcrumbs\BreadcrumbBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ReportController extends Controller
{
    public function index(Request $request): Response
    {
        $reports = Report::query()
            ->with([
                'participants.user',
                'participants.role',
                'categories:id,name,slug',
                'tags:id,name,slug',
                'media',
                'seo',
            ])
            ->when(
                $request->filled('search'),
                function (Builder $query) use ($request): void {
                    $search = $request->string('search')->toString();

                    $query->where(function (Builder $query) use ($search): void {
                        $query
                            ->where('title', 'like', "%{$search}%")
                            ->orWhere('slug', 'like', "%{$search}%")
                            ->orWhere('subtitle', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%")
                            ->orWhere('summary', 'like', "%{$search}%");
                    });
                }
            )
            ->when(
                $request->filled('status'),
                fn (Builder $query) => $query->where('status', $request->input('status'))
            )
            ->when(
                $request->filled('type'),
                fn (Builder $query) => $query->where('type', $request->input('type'))
            )
            ->when(
                $request->filled('featured'),
                fn (Builder $query) => $query->where('featured', $request->boolean('featured'))
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total' => Report::query()->count(),
            'draft' => Report::query()->where('status', ReportStatus::Draft)->count(),
            'published' => Report::query()->where('status', ReportStatus::Published)->count(),
            'featured' => Report::query()->where('featured', true)->count(),
        ];

        return Inertia::render('admin/report/Index', [
            'reports' => ReportResource::collection($reports),
            'stats' => $stats,
            'filters' => [
                'search' => $request->string('search')->toString(),
                'status' => $request->string('status')->toString(),
                'type' => $request->string('type')->toString(),
                'featured' => $request->input('featured', ''),
            ],
            'statusOptions' => ReportStatus::dropdown(),
            'typeOptions' => ReportType::dropdown(),
            'breadcrumbs' => BreadcrumbBuilder::make()
                ->admin()
                ->add('Reports')
                ->toArray(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/report/Create', [
            'statusOptions' => ReportStatus::dropdown(),
            'typeOptions' => ReportType::dropdown(),
            'breadcrumbs' => BreadcrumbBuilder::make()
                ->admin()
                ->add('Reports', route('admin.report.index'))
                ->add('Create')
                ->toArray(),
        ]);
    }

    public function store(
        StoreReportRequest $request,
        CreateReport $action,
    ): RedirectResponse {
        $report = $action->handle($request->validated());

        return redirect()
            ->route('admin.report.show', $report)
            ->with('success', 'Report created successfully.');
    }

    public function show(Report $report): Response
    {
        $report->load([
            'participants.user',
            'participants.role',
            'categories:id,name,slug',
            'tags:id,name,slug',
            'media',
            'seo',
            'sections.blocks',
        ]);

        return Inertia::render('admin/report/Show', [
            'report' => ReportResource::make($report),
            'breadcrumbs' => BreadcrumbBuilder::make()
                ->admin()
                ->add('Reports', route('admin.report.index'))
                ->add($report->title, route('admin.report.show', $report))
                ->toArray(),
        ]);
    }

    public function edit(Report $report): Response
    {
        $report->load([
            'participants.user',
            'participants.role',
            'categories:id,name,slug',
            'tags:id,name,slug',
            'media',
            'seo',
        ]);

        return Inertia::render('admin/report/Edit', [
            'report' => ReportResource::make($report),
            'statusOptions' => ReportStatus::dropdown(),
            'typeOptions' => ReportType::dropdown(),
            'breadcrumbs' => BreadcrumbBuilder::make()
                ->admin()
                ->add('Reports', route('admin.report.index'))
                ->add($report->title, route('admin.report.show', $report))
                ->add('Edit')
                ->toArray(),
        ]);
    }

    public function update(
        UpdateReportRequest $request,
        Report $report,
        UpdateReport $action,
    ): RedirectResponse {
        $action->handle($report, $request->validated());

        return redirect()
            ->route('admin.report.show', $report)
            ->with('success', 'Report updated successfully.');
    }

    public function destroy(
        Report $report,
        DeleteReport $action,
    ): RedirectResponse {
        $action->handle($report);

        return redirect()
            ->route('admin.report.index')
            ->with('success', 'Report deleted successfully.');
    }
}
