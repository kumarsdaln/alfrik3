<?php

namespace App\Http\Controllers\Admin\Survey;

use App\Actions\Survey\CreateSurveyQuestionOption;
use App\Actions\Survey\DeleteSurveyQuestionOption;
use App\Actions\Survey\UpdateSurveyQuestionOption;
use App\Http\Controllers\Controller;
use App\Http\Requests\Survey\StoreSurveyQuestionOptionRequest;
use App\Http\Requests\Survey\UpdateSurveyQuestionOptionRequest;
use App\Http\Resources\Survey\SurveyQuestionOptionResource;
use App\Http\Resources\Survey\SurveyQuestionResource;
use App\Models\Survey\Survey;
use App\Models\Survey\SurveyQuestion;
use App\Models\Survey\SurveyQuestionOption;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class SurveyQuestionOptionController extends Controller
{
    public function index(Survey $survey, SurveyQuestion $question): Response
    {
        $question->load('survey');

        $options = $question->options()
            ->orderBy('position')
            ->get();

        return Inertia::render('admin/survey/question/option/Index', [
            'question' => SurveyQuestionResource::make($question),

            'survey' => [
                'id' => $question->survey->id,
                'title' => $question->survey->title,
            ],

            'options' => SurveyQuestionOptionResource::collection($options),
        ]);
    }

    public function create(Survey $survey, SurveyQuestion $question): Response
    {
        $question->load('survey');

        return Inertia::render('admin/survey/question/option/Create', [
            'question' => SurveyQuestionResource::make($question),

            'survey' => [
                'id' => $question->survey->id,
                'title' => $question->survey->title,
            ],
        ]);
    }

    public function store(
        StoreSurveyQuestionOptionRequest $request,
        Survey $survey,
        SurveyQuestion $question,
        CreateSurveyQuestionOption $action,
    ): RedirectResponse {
        $action->handle(
            $question,
            $request->validated(),
        );

        return back()->with(
            'success',
            'Question option created successfully.'
        );
    }

    public function edit(
        SurveyQuestion $question,
        SurveyQuestionOption $option,
    ): Response {
        abort_unless(
            $option->question_id === $question->id,
            404
        );

        $question->load('survey');

        return Inertia::render('admin/survey/question/option/Edit', [
            'question' => SurveyQuestionResource::make($question),

            'survey' => [
                'id' => $question->survey->id,
                'title' => $question->survey->title,
            ],

            'option' => new SurveyQuestionOptionResource($option),
        ]);
    }

    public function update(
        UpdateSurveyQuestionOptionRequest $request,
        SurveyQuestion $question,
        SurveyQuestionOption $option,
        UpdateSurveyQuestionOption $action,
    ): RedirectResponse {
        abort_unless(
            $option->question_id === $question->id,
            404
        );

        $action->handle(
            $option,
            $request->validated(),
        );

        return redirect()->route(
            'admin.survey.question.options.index',
            $question
        )->with(
            'success',
            'Question option updated successfully.'
        );
    }

    public function destroy(
        SurveyQuestion $question,
        SurveyQuestionOption $option,
        DeleteSurveyQuestionOption $action,
    ): RedirectResponse {
        abort_unless(
            $option->question_id === $question->id,
            404
        );

        $action->handle($option);

        return back()->with(
            'success',
            'Question option deleted successfully.'
        );
    }
}