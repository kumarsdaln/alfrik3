<?php

namespace App\Http\Controllers\Admin\Report;

use App\Actions\Report\CreateReportContentBlock;
use App\Actions\Report\DeleteReportContentBlock;
use App\Actions\Report\UpdateReportContentBlock;
use App\Enums\Report\ReportContentBlockType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Report\StoreReportContentBlockRequest;
use App\Http\Requests\Report\UpdateReportContentBlockRequest;
use App\Http\Resources\Report\ReportContentBlockResource;
use App\Models\Report\Report;
use App\Models\Report\ReportContentBlock;
use App\Models\Report\ReportSection;
use App\Support\Breadcrumbs\BreadcrumbBuilder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ReportContentBlockController extends Controller
{
    private const int PER_PAGE = 20;

    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(
        Request $request,
        Report $report,
        ReportSection $section,
    ): Response {
        abort_unless(
            (int) $section->report_id === (int) $report->id,
            404
        );

        $blocks = $section->blocks()
            ->paginate(self::PER_PAGE)
            ->withQueryString();

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
                'blocks' => ReportContentBlockResource::collection($blocks),
                'breadcrumbs' => BreadcrumbBuilder::make()
                ->admin()
                ->add('Reports', route('admin.report.index'))
                ->add($report->title, route('admin.report.show', $report))
                ->add('Sections', route('admin.report.sections.index', $report))
                ->add($section->title, route('admin.report.sections.show', [
                    'report' => $report->id,
                    'section' => $section->id,
                ]))
                ->add('Content Blocks')
                ->toArray(),
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create(
        Report $report,
        ReportSection $section,
    ): Response {
        abort_unless(
            (int) $section->report_id === (int) $report->id,
            404
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
                'breadcrumbs' => BreadcrumbBuilder::make()
                ->admin()
                ->add('Reports', route('admin.report.index'))
                ->add($report->title, route('admin.report.show', $report))
                ->add('Sections', route('admin.report.sections.index', $report))
                ->add($section->title, route('admin.report.sections.index', $section->report_id))
                ->add('Content Blocks', route('admin.report.sections.blocks.index', [
                    'report' => $report->id,
                    'section' => $section->id,
                ]))
                ->add('Create')
                ->toArray(),
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store(
        StoreReportContentBlockRequest $request,
        Report $report,
        ReportSection $section,
        CreateReportContentBlock $action,
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
            ->route('admin.report.sections.blocks.index', [
                'report' => $report->id,
                'section' => $section->id,
            ])
            ->with('success', 'Content block created successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Show
    |--------------------------------------------------------------------------
    */

    public function show(
        Report $report,
        ReportSection $section,
        ReportContentBlock $block,
    ): Response {
        abort_unless(
            (int) $section->report_id === (int) $report->id,
            404
        );

        abort_unless(
            (int) $block->report_section_id === (int) $section->id,
            404
        );

        return Inertia::render(
            'admin/report/section/block/Show',
            [
                'report' => [
                    'id' => $report->id,
                    'title' => $report->title,
                ],
                'section' => [
                    'id' => $section->id,
                    'title' => $section->title,
                ],
                'block' => new ReportContentBlockResource($block),
                'breadcrumbs' => BreadcrumbBuilder::make()
                    ->admin()
                    ->add('Reports', route('admin.report.index'))
                    ->add($report->title, route('admin.report.show', $report))
                    ->add('Sections', route('admin.report.sections.index', $report))
                    ->add($section->title, route('admin.report.sections.index', [
                        'report' => $report->id,
                        'section' => $section->id,
                    ]))
                    ->add('Content Blocks', route('admin.report.sections.blocks.index', [
                        'report' => $report->id,
                        'section' => $section->id,
                    ]))
                    ->add($block->title)
                    ->toArray(),
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit(
        Report $report,
        ReportSection $section,
        ReportContentBlock $block,
    ): Response {
        abort_unless(
            (int) $section->report_id === (int) $report->id,
            404
        );

        abort_unless(
            (int) $block->report_section_id === (int) $section->id,
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
                'block' => new ReportContentBlockResource($block),
                'typeOptions' => ReportContentBlockType::dropdown(),
                'breadcrumbs' => BreadcrumbBuilder::make()
                    ->admin()
                    ->add('Reports', route('admin.report.index'))
                    ->add($report->title, route('admin.report.show', $report))
                    ->add($section->title, route('admin.report.sections.index', [
                        'report' => $report->id,
                        'section' => $section->id,
                    ]))
                    ->add($block->title, route('admin.report.sections.blocks.show', [
                        'report' => $report->id,
                        'section' => $section->id,
                        'block' => $block->id,
                    ]))
                    ->add('Edit')
                    ->toArray(),
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(
        UpdateReportContentBlockRequest $request,
        Report $report,
        ReportSection $section,
        ReportContentBlock $block,
        UpdateReportContentBlock $action,
    ): RedirectResponse {
        abort_unless(
            (int) $section->report_id === (int) $report->id,
            404
        );

        abort_unless(
            (int) $block->report_section_id === (int) $section->id,
            404
        );

        $action->handle(
            block: $block,
            data: $request->validated(),
        );

        return redirect()
            ->route('admin.report.sections.blocks.index', [
                'report' => $report->id,
                'section' => $section->id,
            ])
            ->with('success', 'Content block updated successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Destroy
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Report $report,
        ReportSection $section,
        ReportContentBlock $block,
        DeleteReportContentBlock $action,
    ): RedirectResponse {
        abort_unless(
            (int) $section->report_id === (int) $report->id,
            404
        );

        abort_unless(
            (int) $block->report_section_id === (int) $section->id,
            404
        );

        $action->handle($block);

        return redirect()
            ->route('admin.report.sections.blocks.index', [
                'report' => $report->id,
                'section' => $section->id,
            ])
            ->with('success', 'Content block deleted successfully.');
    }
}