<?php

namespace App\Http\Controllers\Admin\Survey;

use App\Actions\Survey\CreateSurveyQuestion;
use App\Actions\Survey\DeleteSurveyQuestion;
use App\Actions\Survey\UpdateSurveyQuestion;
use App\Enums\Survey\SurveyQuestionType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Survey\StoreSurveyQuestionRequest;
use App\Http\Requests\Admin\Survey\UpdateSurveyQuestionRequest;
use App\Http\Resources\Admin\Survey\SurveyQuestionResource;
use App\Models\Survey\Survey;
use App\Models\Survey\SurveyQuestion;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class SurveyQuestionController extends Controller
{
    public function index(Survey $survey): Response
    {
        $questions = $survey->questions()
            ->with('section')
            ->orderBy('position')
            ->get();

        $sections = $survey->sections()
            ->orderBy('position')
            ->get([
                'id',
                'survey_id',
                'title',
                'position',
            ]);

        return Inertia::render('admin/survey/Questions', [
            'survey' => [
                'id' => $survey->id,
                'title' => $survey->title,
            ],

            'questions' => SurveyQuestionResource::collection($questions),

            'sections' => $sections,

            'typeOptions' => SurveyQuestionType::dropdown(),
        ]);
    }

    public function store(
        StoreSurveyQuestionRequest $request,
        Survey $survey,
        CreateSurveyQuestion $action,
    ): RedirectResponse {
        $action->handle(
            $survey,
            $request->validated(),
        );

        return back()->with(
            'success',
            'Survey question created successfully.'
        );
    }

    public function edit(
        Survey $survey,
        SurveyQuestion $question,
    ): Response {
        abort_unless(
            $question->survey_id === $survey->id,
            404
        );

        $question->load('section');

        $sections = $survey->sections()
            ->orderBy('position')
            ->get([
                'id',
                'survey_id',
                'title',
                'position',
            ]);

        return Inertia::render('admin/survey/question/Edit', [
            'survey' => [
                'id' => $survey->id,
                'title' => $survey->title,
            ],

            'question' => new SurveyQuestionResource($question),

            'sections' => $sections,

            'typeOptions' => SurveyQuestionType::dropdown(),
        ]);
    }

    public function update(
        UpdateSurveyQuestionRequest $request,
        Survey $survey,
        SurveyQuestion $question,
        UpdateSurveyQuestion $action,
    ): RedirectResponse {
        abort_unless(
            $question->survey_id === $survey->id,
            404
        );

        $action->handle(
            $question,
            $request->validated(),
        );

        return redirect()->route(
            'admin.survey.questions.index',
            $survey
        )->with(
            'success',
            'Survey question updated successfully.'
        );
    }

    public function destroy(
        Survey $survey,
        SurveyQuestion $question,
        DeleteSurveyQuestion $action,
    ): RedirectResponse {
        abort_unless(
            $question->survey_id === $survey->id,
            404
        );

        $action->handle($question);

        return back()->with(
            'success',
            'Survey question deleted successfully.'
        );
    }
}