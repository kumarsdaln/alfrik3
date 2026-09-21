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
use App\Models\Research\Research;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ReportController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(Request $request): Response
    {
        $reports = Report::query()
            ->with([
                'research:id,title',
                'author:id,name',
            ])
            ->when(
                $request->filled('search'),
                function ($query) use ($request) {
                    $search = $request->string('search');

                    $query->where(function ($query) use ($search) {
                        $query
                            ->where('title', 'like', "%{$search}%")
                            ->orWhere(
                                'subtitle',
                                'like',
                                "%{$search}%"
                            );
                    });
                }
            )
            ->when(
                $request->filled('status'),
                fn($query) => $query->where(
                    'status',
                    $request->string('status')
                )
            )
            ->when(
                $request->filled('type'),
                fn($query) => $query->where(
                    'type',
                    $request->string('type')
                )
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total' => Report::count(),

            'draft' => Report::where(
                'status',
                ReportStatus::Draft
            )->count(),

            'published' => Report::where(
                'status',
                ReportStatus::Published
            )->count(),

            'featured' => Report::where(
                'featured',
                true
            )->count(),
        ];

        return Inertia::render('admin/report/Index', [
            'reports' => ReportResource::collection($reports),

            'stats' => $stats,

            'filters' => [
                'search' => $request->string('search')->toString(),

                'status' => $request->string('status')->toString(),

                'type' => $request->string('type')->toString(),
            ],

            'statusOptions' => ReportStatus::dropdown(),

            'typeOptions' => ReportType::dropdown(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create(): Response
    {
        return Inertia::render('admin/report/Create', [
            'statusOptions' => ReportStatus::dropdown(),
            'typeOptions' => ReportType::dropdown(),

            'researchOptions' => Research::query()
                ->orderBy('title')
                ->get(['id', 'title'])
                ->map(fn(Research $research) => [
                    'value' => $research->id,
                    'label' => $research->title,
                ])
                ->values()
                ->all(),

            'authorOptions' => User::query()
                ->orderBy('name')
                ->get(['id', 'name'])
                ->map(fn(User $user) => [
                    'value' => $user->id,
                    'label' => $user->name,
                ])
                ->values()
                ->all(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store(
        StoreReportRequest $request,
        CreateReport $action,
    ): RedirectResponse {
        $report = $action->handle(
            $request->validated()
        );

        return redirect()
            ->route('admin.report.show', $report)
            ->with(
                'success',
                'Report created successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Show
    |--------------------------------------------------------------------------
    */

    public function show(Report $report): Response
    {
        $report->load([
            'research:id,title',
            'author:id,name',
            'sections.contentBlocks',
        ]);

        return Inertia::render('admin/report/Show', [
            'report' => new ReportResource($report),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit(Report $report): Response
    {
        return Inertia::render('admin/report/Edit', [
            'report' => new ReportResource($report),

            'statusOptions' => ReportStatus::dropdown(),

            'typeOptions' => ReportType::dropdown(),

            'researchOptions' => Research::query()
                ->orderBy('title')
                ->get(['id', 'title'])
                ->map(fn(Research $research) => [
                    'value' => $research->id,
                    'label' => $research->title,
                ])
                ->values()
                ->all(),

            'authorOptions' => User::query()
                ->orderBy('name')
                ->get(['id', 'name'])
                ->map(fn(User $user) => [
                    'value' => $user->id,
                    'label' => $user->name,
                ])
                ->values()
                ->all(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(
        UpdateReportRequest $request,
        Report $report,
        UpdateReport $action,
    ): RedirectResponse {
        $action->handle(
            $report,
            $request->validated()
        );

        return redirect()
            ->route('admin.report.show', $report)
            ->with(
                'success',
                'Report updated successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Destroy
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Report $report,
        DeleteReport $action,
    ): RedirectResponse {
        $action->handle($report);

        return redirect()
            ->route('admin.report.index')
            ->with(
                'success',
                'Report deleted successfully.'
            );
    }
}
