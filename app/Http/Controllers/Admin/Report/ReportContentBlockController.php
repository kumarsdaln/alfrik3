<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Report\CreateReportContentBlock;
use App\Actions\Report\DeleteReportContentBlock;
use App\Actions\Report\UpdateReportContentBlock;
use App\Enums\Report\ReportContentBlockType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Report\StoreReportContentBlockRequest;
use App\Http\Requests\Admin\Report\UpdateReportContentBlockRequest;
use App\Http\Resources\Report\ReportContentBlockResource;
use App\Models\Report\Report;
use App\Models\Report\ReportContentBlock;
use App\Models\Report\ReportSection;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ReportContentBlockController extends Controller
{
    public function index(
        Report $report,
        ReportSection $section,
    ): Response {
        $this->ensureSectionBelongsToReport(
            $report,
            $section,
        );

        $section->load([
            'contentBlocks',
        ]);

        return Inertia::render(
            'admin/report/section/block/Index',
            [
                'report' => [
                    'id' => $report->id,
                    'title' => $report->title,
                ],

                'section' => [
                    'id' => $section->id,
                    'title' => $section->title,
                ],

                'blocks' => ReportContentBlockResource::collection(
                    $section->contentBlocks
                ),
            ]
        );
    }

    public function create(
        Report $report,
        ReportSection $section,
    ): Response {
        $this->ensureSectionBelongsToReport(
            $report,
            $section,
        );

        return Inertia::render(
            'admin/report/section/block/Create',
            [
                'report' => [
                    'id' => $report->id,
                    'title' => $report->title,
                ],

                'section' => [
                    'id' => $section->id,
                    'title' => $section->title,
                ],

                'typeOptions' => ReportContentBlockType::dropdown(),
            ]
        );
    }

    public function store(
        StoreReportContentBlockRequest $request,
        Report $report,
        ReportSection $section,
        CreateReportContentBlock $action,
    ): RedirectResponse {
        $this->ensureSectionBelongsToReport(
            $report,
            $section,
        );

        $action->handle(
            section: $section,
            data: $request->validated(),
        );

        return redirect()
            ->route(
                'admin.report.sections.blocks.index',
                [
                    'report' => $report,
                    'section' => $section,
                ]
            )
            ->with(
                'success',
                'Content block created successfully.'
            );
    }

    public function edit(
        Report $report,
        ReportSection $section,
        ReportContentBlock $block,
    ): Response {
        $this->ensureSectionBelongsToReport(
            $report,
            $section,
        );

        abort_unless(
            $block->report_section_id === $section->id,
            404
        );

        return Inertia::render(
            'admin/report/section/block/Edit',
            [
                'report' => [
                    'id' => $report->id,
                    'title' => $report->title,
                ],

                'section' => [
                    'id' => $section->id,
                    'title' => $section->title,
                ],

                'block' => new ReportContentBlockResource(
                    $block
                ),

                'typeOptions' => ReportContentBlockType::dropdown(),
            ]
        );
    }

    public function update(
        UpdateReportContentBlockRequest $request,
        Report $report,
        ReportSection $section,
        ReportContentBlock $block,
        UpdateReportContentBlock $action,
    ): RedirectResponse {
        $this->ensureSectionBelongsToReport(
            $report,
            $section,
        );

        abort_unless(
            $block->report_section_id === $section->id,
            404
        );

        $action->handle(
            block: $block,
            data: $request->validated(),
        );

        return redirect()
            ->route(
                'admin.report.sections.blocks.index',
                [
                    'report' => $report,
                    'section' => $section,
                ]
            )
            ->with(
                'success',
                'Content block updated successfully.'
            );
    }

    public function destroy(
        Report $report,
        ReportSection $section,
        ReportContentBlock $block,
        DeleteReportContentBlock $action,
    ): RedirectResponse {
        $this->ensureSectionBelongsToReport(
            $report,
            $section,
        );

        abort_unless(
            $block->report_section_id === $section->id,
            404
        );

        $action->handle($block);

        return redirect()
            ->route(
                'admin.report.sections.blocks.index',
                [
                    'report' => $report,
                    'section' => $section,
                ]
            )
            ->with(
                'success',
                'Content block deleted successfully.'
            );
    }

    private function ensureSectionBelongsToReport(
        Report $report,
        ReportSection $section,
    ): void {
        abort_unless(
            $section->report_id === $report->id,
            404
        );
    }
}
