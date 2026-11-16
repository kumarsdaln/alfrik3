<?php

namespace App\Http\Controllers\Admin\Report;

use App\Actions\Report\CreateReportSection;
use App\Actions\Report\DeleteReportSection;
use App\Actions\Report\UpdateReportSection;
use App\Http\Controllers\Controller;
use App\Http\Requests\Report\StoreReportSectionRequest;
use App\Http\Requests\Report\UpdateReportSectionRequest;
use App\Http\Resources\Report\ReportResource;
use App\Http\Resources\Report\ReportSectionResource;
use App\Models\Report\Report;
use App\Models\Report\ReportSection;
use App\Support\Breadcrumbs\BreadcrumbBuilder;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ReportSectionController extends Controller
{
    public function index(Report $report): Response
    {
        $report->load([
            'sections.blocks',
        ]);

        return Inertia::render(
            'admin/report/section/Index',
            [
                'report' => new ReportResource($report),

                'sections' => ReportSectionResource::collection(
                    $report->sections
                ),
                'breadcrumbs' => BreadcrumbBuilder::make()
                ->admin()
                ->add('Reports', route('admin.report.index'))
                ->add($report->title, route('admin.report.show', $report))
                ->add('Sections')
                ->toArray(),
            ]
        );
    }

    public function create(Report $report): Response
    {
        return Inertia::render(
            'admin/report/section/Create',
            [
                'report' => [
                    'id' => $report->id,
                    'title' => $report->title,
                ],
                'breadcrumbs' => BreadcrumbBuilder::make()
                ->admin()
                ->add('Reports', route('admin.report.index'))
                ->add($report->title, route('admin.report.show', $report))
                ->add('Sections', route('admin.report.sections.index', $report))
                ->add('Create')
                ->toArray(),
            ]
        );
    }

    public function store(
        StoreReportSectionRequest $request,
        Report $report,
        CreateReportSection $action,
    ): RedirectResponse {
        $action->handle(
            report: $report,
            data: $request->validated(),
        );

        return redirect()
            ->route('admin.report.sections.index', [
                'report' => $report->id,
            ])
            ->with('success', 'Report section created successfully.');
    }

    public function show(
        Report $report,
        ReportSection $section,
    ): Response {
        abort_unless(
            (int) $section->report_id === (int) $report->id,
            404
        );

        $section->load([
            'blocks',
        ]);

        return Inertia::render(
            'admin/report/section/Show',
            [
                'report' => [
                    'id' => $report->id,
                    'title' => $report->title,
                ],

                'section' => new ReportSectionResource($section),
                'breadcrumbs' => BreadcrumbBuilder::make()
                ->admin()
                ->add('Reports', route('admin.report.index'))
                ->add($report->title, route('admin.report.show', $report))
                ->add('Sections', route('admin.report.sections.index', $report))
                ->add($section->title)
                ->toArray(),
            ]
        );
    }

    public function edit(
        Report $report,
        ReportSection $section,
    ): Response {
        abort_unless(
            (int) $section->report_id === (int) $report->id,
            404
        );

        return Inertia::render(
            'admin/report/section/Edit',
            [
                'report' => [
                    'id' => $report->id,
                    'title' => $report->title,
                ],

                'section' => new ReportSectionResource($section),
                'breadcrumbs' => BreadcrumbBuilder::make()
                ->admin()
                ->add('Reports', route('admin.report.index'))
                ->add($report->title, route('admin.report.show', $report))
                ->add('Sections', route('admin.report.sections.index', $report))
                ->add($section->title, route('admin.report.sections.show', [
                    'report' => $report->id,
                    'section' => $section->id,
                ]))
                ->add('Edit')
                ->toArray(),
            ]
        );
    }

    public function update(
        UpdateReportSectionRequest $request,
        Report $report,
        ReportSection $section,
        UpdateReportSection $action,
    ): RedirectResponse {
        abort_unless(
            (int) $section->report_id === (int) $report->id,
            404
        );

        $action->handle(
            section: $section,
            data: $request->validated(),
        );

        return redirect()
            ->route('admin.report.sections.index', [
                'report' => $report->id,
            ])
            ->with('success', 'Report section updated successfully.');
    }

    public function destroy(
        Report $report,
        ReportSection $section,
        DeleteReportSection $action,
    ): RedirectResponse {
        abort_unless(
            (int) $section->report_id === (int) $report->id,
            404
        );

        $action->handle($section);

        return redirect()
            ->route('admin.report.sections.index', [
                'report' => $report->id,
            ])
            ->with('success', 'Report section deleted successfully.');
    }
}