<?php

namespace App\Http\Controllers\Admin\Magazine;

use App\Actions\Magazine\ArchiveMagazineIssue;
use App\Actions\Magazine\CreateMagazineIssue;
use App\Actions\Magazine\DeleteMagazineIssue;
use App\Actions\Magazine\PublishMagazineIssue;
use App\Actions\Magazine\UpdateMagazineIssue;
use App\Enums\Magazine\MagazineIssueStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Magazine\StoreMagazineIssueRequest;
use App\Http\Requests\Magazine\UpdateMagazineIssueRequest;
use App\Http\Resources\Magazine\MagazineIssueResource;
use App\Models\Magazine\Magazine;
use App\Models\Magazine\MagazineIssue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MagazineIssueController extends Controller
{
    public function index(
        Request $request,
        Magazine $magazine,
    ): Response {
        $stats = [
            'total' => $magazine->issues()->count(),

            'published' => $magazine->issues()
                ->where('status', 'published')
                ->count(),

            'draft' => $magazine->issues()
                ->where('status', 'draft')
                ->count(),

            'this_month' => $magazine->issues()
                ->whereBetween('created_at', [
                    now()->startOfMonth(),
                    now()->endOfMonth(),
                ])
                ->count(),
        ];

        $issues = $magazine->issues()
            ->with('media')
            ->when(
                $request->filled('search'),
                fn($query) => $query->where(function ($query) use ($request) {
                    $query
                        ->where('title', 'ilike', '%' . $request->search . '%')
                        ->orWhere('subtitle', 'ilike', '%' . $request->search . '%');
                })
            )
            ->when(
                $request->filled('status'),
                fn($query) => $query->where(
                    'status',
                    $request->string('status')
                )
            )
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate($request->integer('per_page', 20))
            ->withQueryString();

        return Inertia::render('admin/magazines/issues/Index', [
            'magazine' => $magazine,
            'issues' => MagazineIssueResource::collection($issues),
            'statusOptions' => $this->statusOptions(),
            'filters' => [
                'search' => $request->search,
                'status' => $request->status,
            ],
            'stats' => $stats,
        ]);
    }

    public function create(Magazine $magazine): Response
    {
        return Inertia::render('admin/magazines/issues/Create', [
            'magazine' => $magazine,
            'statusOptions' => $this->statusOptions(),
        ]);
    }

    public function store(
        StoreMagazineIssueRequest $request,
        Magazine $magazine,
        CreateMagazineIssue $action,
    ): RedirectResponse {
        $issue = $action->handle(
            magazine: $magazine,
            data: $request->validated(),
        );

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('Magazine issue created successfully.'),
        ]);

        return to_route(
            'admin.magazines.issues.edit',
            [
                'magazine' => $magazine,
                'issue' => $issue,
            ],
        );
    }

    public function show(
        Magazine $magazine,
        MagazineIssue $issue,
    ): Response {
        $issue->load([
            'media',
            'articles',
        ]);

        return Inertia::render('admin/magazines/issues/Show', [
            'magazine' => $magazine,
            'issue' => new MagazineIssueResource($issue),
        ]);
    }

    public function edit(
        Magazine $magazine,
        MagazineIssue $issue,
    ): Response {
        $issue->load('media');

        return Inertia::render('admin/magazines/issues/Edit', [
            'magazine' => $magazine,
            'issue' => new MagazineIssueResource($issue),
            'statusOptions' => $this->statusOptions(),
        ]);
    }

    public function update(
        UpdateMagazineIssueRequest $request,
        Magazine $magazine,
        MagazineIssue $issue,
        UpdateMagazineIssue $action,
    ): RedirectResponse {
        $action->handle(
            issue: $issue,
            data: $request->validated(),
        );

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('Magazine issue updated successfully.'),
        ]);

        return to_route(
            'admin.magazines.issues.index',
            $magazine,
        );
    }

    public function destroy(
        Magazine $magazine,
        MagazineIssue $issue,
        DeleteMagazineIssue $action,
    ): RedirectResponse {
        $action->handle($issue);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('Magazine issue deleted successfully.'),
        ]);

        return to_route(
            'admin.magazines.issues.index',
            $magazine,
        );
    }

    public function publish(
        Magazine $magazine,
        MagazineIssue $issue,
        PublishMagazineIssue $action,
    ): RedirectResponse {
        $action->handle($issue);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('Magazine issue published successfully.'),
        ]);

        return back();
    }

    public function archive(
        Magazine $magazine,
        MagazineIssue $issue,
        ArchiveMagazineIssue $action,
    ): RedirectResponse {
        $action->handle($issue);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('Magazine issue archived successfully.'),
        ]);

        return back();
    }

    private function statusOptions(): array
    {
        return collect(MagazineIssueStatus::cases())
            ->map(
                fn(MagazineIssueStatus $status) => $status->option()
            )
            ->values()
            ->all();
    }
}
