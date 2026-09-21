<?php

namespace App\Http\Controllers\Admin\Research;

use App\Actions\Research\CreateResearchQuestion;
use App\Actions\Research\DeleteResearchQuestion;
use App\Actions\Research\UpdateResearchQuestion;
use App\Enums\Research\ResearchQuestionType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Research\StoreResearchQuestionRequest;
use App\Http\Requests\Research\UpdateResearchQuestionRequest;
use App\Models\Research\Research;
use App\Models\Research\ResearchQuestion;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ResearchQuestionController extends Controller
{
    public function index(Research $research): Response
    {
        $research->load('questions');

        return Inertia::render('admin/research/Questions', [
            'research' => [
                'id' => $research->id,
                'title' => $research->title,
            ],
            'questions' => $research->questions,
            'questionTypeOptions' => ResearchQuestionType::dropdown(),
        ]);
    }

    public function store(
        StoreResearchQuestionRequest $request,
        Research $research,
        CreateResearchQuestion $action,
    ): RedirectResponse {
        $action->handle(
            research: $research,
            data: $request->validated(),
        );

        return back();
    }

    public function edit(
        Research $research,
        ResearchQuestion $question,
    ): Response {
        abort_unless(
            $question->research_id === $research->id,
            404
        );

        return Inertia::render('admin/research/question/Edit', [
            'research' => [
                'id' => $research->id,
                'title' => $research->title,
            ],
            'question' => $question,
            'questionTypeOptions' => ResearchQuestionType::dropdown(),
        ]);
    }

    public function update(
        UpdateResearchQuestionRequest $request,
        Research $research,
        ResearchQuestion $question,
        UpdateResearchQuestion $action,
    ): RedirectResponse {
        abort_unless(
            $question->research_id === $research->id,
            404
        );

        $action->handle(
            question: $question,
            data: $request->validated(),
        );

        return back();
    }

    public function destroy(
        Research $research,
        ResearchQuestion $question,
        DeleteResearchQuestion $action,
    ): RedirectResponse {
        abort_unless(
            $question->research_id === $research->id,
            404
        );

        $action->handle($question);

        return back();
    }
}