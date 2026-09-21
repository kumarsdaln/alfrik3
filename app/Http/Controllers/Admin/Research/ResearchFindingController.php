<?php

namespace App\Http\Controllers\Admin\Research;

use App\Actions\Research\CreateResearchFinding;
use App\Actions\Research\DeleteResearchFinding;
use App\Actions\Research\UpdateResearchFinding;
use App\Enums\Research\ResearchFindingType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Research\StoreResearchFindingRequest;
use App\Http\Requests\Research\UpdateResearchFindingRequest;
use App\Models\Research\Research;
use App\Models\Research\ResearchFinding;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ResearchFindingController extends Controller
{
    public function index(Research $research): Response
    {
        $research->load('findings');

        return Inertia::render('admin/research/Findings', [
            'research' => [
                'id' => $research->id,
                'title' => $research->title,
            ],
            'findings' => $research->findings,
            'findingTypeOptions' => ResearchFindingType::dropdown(),
        ]);
    }

    public function store(
        StoreResearchFindingRequest $request,
        Research $research,
        CreateResearchFinding $action,
    ): RedirectResponse {
        $action->handle(
            research: $research,
            data: $request->validated(),
        );

        return back();
    }

    public function show(
        Research $research,
        ResearchFinding $finding,
    ): Response {
        abort_unless(
            $finding->research_id === $research->id,
            404
        );

        $finding->load([
            'questions',
            'evidence',
        ]);

        return Inertia::render('admin/research/finding/Show', [
            'research' => [
                'id' => $research->id,
                'title' => $research->title,
            ],
            'finding' => new ResearchFindingResource($finding),
            'questions' => ResearchQuestionResource::collection(
                $finding->questions
            ),
            'evidence' => ResearchEvidenceResource::collection(
                $finding->evidence
            ),
        ]);
    }

    public function edit(
        Research $research,
        ResearchFinding $finding,
    ): Response {
        abort_unless(
            $finding->research_id === $research->id,
            404
        );

        return Inertia::render('admin/research/finding/Edit', [
            'research' => [
                'id' => $research->id,
                'title' => $research->title,
            ],
            'finding' => $finding,
            'findingTypeOptions' => ResearchFindingType::dropdown(),
        ]);
    }

    public function update(
        UpdateResearchFindingRequest $request,
        Research $research,
        ResearchFinding $finding,
        UpdateResearchFinding $action,
    ): RedirectResponse {
        abort_unless(
            $finding->research_id === $research->id,
            404
        );

        $action->handle(
            finding: $finding,
            data: $request->validated(),
        );

        return back();
    }

    public function destroy(
        Research $research,
        ResearchFinding $finding,
        DeleteResearchFinding $action,
    ): RedirectResponse {
        abort_unless(
            $finding->research_id === $research->id,
            404
        );

        $action->handle($finding);

        return back();
    }
}
