<?php

namespace App\Http\Controllers\Admin\Research;

use App\Actions\Research\SyncResearchFindingQuestions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Research\SyncResearchFindingQuestionsRequest;
use App\Http\Resources\Research\ResearchQuestionResource;
use App\Models\Research\Research;
use App\Models\Research\ResearchFinding;
use Inertia\Inertia;
use Inertia\Response;

class ResearchFindingQuestionController extends Controller
{
    public function edit(
        Research $research,
        ResearchFinding $finding,
    ): Response {
        abort_unless(
            $finding->research_id === $research->id,
            404
        );

        $questions = $research->questions()
            ->orderBy('position')
            ->get();

        $selectedQuestionIds = $finding->questions()
            ->pluck('id')
            ->values()
            ->all();

        return Inertia::render(
            'admin/research/finding/questions/Edit',
            [
                'research' => [
                    'id' => $research->id,
                    'title' => $research->title,
                ],

                'finding' => [
                    'id' => $finding->id,
                    'title' => $finding->title,
                ],

                'questions' => ResearchQuestionResource::collection(
                    $questions
                ),

                'selectedQuestionIds' => $selectedQuestionIds,
            ]
        );
    }

    public function update(
        SyncResearchFindingQuestionsRequest $request,
        Research $research,
        ResearchFinding $finding,
        SyncResearchFindingQuestions $action,
    ) {
        abort_unless(
            $finding->research_id === $research->id,
            404
        );

        $action->handle(
            $finding,
            $request->validated('question_ids', [])
        );

        return redirect()
            ->route(
                'admin.research.findings.show',
                [
                    'research' => $research,
                    'finding' => $finding,
                ]
            )
            ->with('success', 'Research questions updated successfully.');
    }
}
