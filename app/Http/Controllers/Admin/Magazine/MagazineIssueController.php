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
use App\Models\Magazine\Magazine;
use App\Models\Magazine\MagazineIssue;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class MagazineIssueController extends Controller
{
    public function index(Magazine $magazine): Response
    {
        $issues = $magazine->issues()
            ->with('media')
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('admin/magazines/issues/Index', [
            'magazine' => $magazine,
            'issues' => $issues,
            'statusOptions' => collect(MagazineIssueStatus::cases())
                ->map(fn (MagazineIssueStatus $status) => [
                    'value' => $status->value,
                    'label' => $status->label(),
                    'color' => $status->color(),
                ])
                ->values(),
        ]);
    }

    public function create(Magazine $magazine): Response
    {
        return Inertia::render('admin/magazines/issues/Create', [
            'magazine' => $magazine,
            'statusOptions' => collect(MagazineIssueStatus::cases())
                ->map(fn (MagazineIssueStatus $status) => [
                    'value' => $status->value,
                    'label' => $status->label(),
                    'color' => $status->color(),
                ])
                ->values(),
        ]);
    }

    public function store(
        StoreMagazineIssueRequest $request,
        Magazine $magazine,
        CreateMagazineIssue $action,
    ): RedirectResponse {
        $action->handle(
            $magazine,
            $request->validated()
        );

        return to_route('admin.magazines.issues.index', $magazine)
            ->with('success', 'Magazine issue created successfully.');
    }

    public function edit(
        Magazine $magazine,
        MagazineIssue $issue,
    ): Response {
        return Inertia::render('admin/magazines/issues/Edit', [
            'magazine' => $magazine,
            'issue' => $issue->load('media'),
            'statusOptions' => collect(MagazineIssueStatus::cases())
                ->map(fn (MagazineIssueStatus $status) => [
                    'value' => $status->value,
                    'label' => $status->label(),
                    'color' => $status->color(),
                ])
                ->values(),
        ]);
    }

    public function update(
        UpdateMagazineIssueRequest $request,
        Magazine $magazine,
        MagazineIssue $issue,
        UpdateMagazineIssue $action,
    ): RedirectResponse {
        $action->handle(
            $issue,
            $request->validated()
        );

        return to_route('admin.magazines.issues.index', $magazine)
            ->with('success', 'Magazine issue updated successfully.');
    }

    public function destroy(
        Magazine $magazine,
        MagazineIssue $issue,
        DeleteMagazineIssue $action,
    ): RedirectResponse {
        $action->handle($issue);

        return to_route('admin.magazines.issues.index', $magazine)
            ->with('success', 'Magazine issue deleted successfully.');
    }

    public function publish(
        Magazine $magazine,
        MagazineIssue $issue,
        PublishMagazineIssue $action,
    ): RedirectResponse {
        $action->handle($issue);

        return back()->with('success', 'Magazine issue published successfully.');
    }

    public function archive(
        Magazine $magazine,
        MagazineIssue $issue,
        ArchiveMagazineIssue $action,
    ): RedirectResponse {
        $action->handle($issue);

        return back()->with('success', 'Magazine issue archived successfully.');
    }
}