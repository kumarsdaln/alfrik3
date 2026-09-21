<?php

namespace App\Http\Controllers\Admin\Research;

use App\Actions\Research\CreateResearchEvidence;
use App\Actions\Research\DeleteResearchEvidence;
use App\Actions\Research\UpdateResearchEvidence;
use App\Enums\Research\ResearchEvidenceType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Research\StoreResearchEvidenceRequest;
use App\Http\Requests\Admin\Research\UpdateResearchEvidenceRequest;
use App\Http\Resources\Research\ResearchEvidenceResource;
use App\Models\Research\Research;
use App\Models\Research\ResearchEvidence;
use App\Models\Research\ResearchFinding;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ResearchEvidenceController extends Controller
{
    public function index(
        Research $research,
        ResearchFinding $finding,
    ): Response {
        $this->ensureFindingBelongsToResearch(
            $research,
            $finding
        );

        $evidence = $finding->evidence()
            ->orderBy('position')
            ->get();

        return Inertia::render(
            'admin/research/finding/evidence/Index',
            [
                'research' => [
                    'id' => $research->id,
                    'title' => $research->title,
                ],

                'finding' => [
                    'id' => $finding->id,
                    'title' => $finding->title,
                ],

                'evidence' => ResearchEvidenceResource::collection(
                    $evidence
                ),

                'evidenceTypeOptions' => collect(
                    ResearchEvidenceType::cases()
                )
                    ->map(fn(ResearchEvidenceType $type) => [
                        'value' => $type->value,
                        'label' => $type->label(),
                    ])
                    ->values()
                    ->all(),
            ]
        );
    }

    public function store(
        StoreResearchEvidenceRequest $request,
        Research $research,
        ResearchFinding $finding,
        CreateResearchEvidence $action,
    ): RedirectResponse {
        $this->ensureFindingBelongsToResearch(
            $research,
            $finding
        );

        $action->handle(
            $finding,
            $request->validated()
        );

        return back()->with(
            'success',
            'Evidence added successfully.'
        );
    }

    public function edit(
        Research $research,
        ResearchFinding $finding,
        ResearchEvidence $evidence,
    ): Response {
        $this->ensureFindingBelongsToResearch(
            $research,
            $finding
        );

        abort_unless(
            $evidence->finding_id === $finding->id,
            404
        );

        return Inertia::render(
            'admin/research/finding/evidence/Edit',
            [
                'research' => [
                    'id' => $research->id,
                    'title' => $research->title,
                ],

                'finding' => [
                    'id' => $finding->id,
                    'title' => $finding->title,
                ],

                'evidence' => new ResearchEvidenceResource(
                    $evidence
                ),

                'evidenceTypeOptions' => collect(
                    ResearchEvidenceType::cases()
                )
                    ->map(fn(ResearchEvidenceType $type) => [
                        'value' => $type->value,
                        'label' => $type->label(),
                    ])
                    ->values()
                    ->all(),
            ]
        );
    }

    public function update(
        UpdateResearchEvidenceRequest $request,
        Research $research,
        ResearchFinding $finding,
        ResearchEvidence $evidence,
        UpdateResearchEvidence $action,
    ): RedirectResponse {
        $this->ensureFindingBelongsToResearch(
            $research,
            $finding
        );

        abort_unless(
            $evidence->finding_id === $finding->id,
            404
        );

        $action->handle(
            $evidence,
            $request->validated()
        );

        return redirect()
            ->route(
                'admin.research.findings.evidence.index',
                [
                    'research' => $research,
                    'finding' => $finding,
                ]
            )
            ->with(
                'success',
                'Evidence updated successfully.'
            );
    }

    public function destroy(
        Research $research,
        ResearchFinding $finding,
        ResearchEvidence $evidence,
        DeleteResearchEvidence $action,
    ): RedirectResponse {
        $this->ensureFindingBelongsToResearch(
            $research,
            $finding
        );

        abort_unless(
            $evidence->finding_id === $finding->id,
            404
        );

        $action->handle($evidence);

        return back()->with(
            'success',
            'Evidence deleted successfully.'
        );
    }

    private function ensureFindingBelongsToResearch(
        Research $research,
        ResearchFinding $finding,
    ): void {
        abort_unless(
            $finding->research_id === $research->id,
            404
        );
    }
}
