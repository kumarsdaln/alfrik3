<?php

namespace App\Http\Controllers\Admin\Survey;

use App\Http\Controllers\Controller;
use App\Http\Resources\Survey\SurveyQuestionResource;
use App\Http\Resources\Survey\SurveySectionResource;
use App\Models\Survey\Survey;
use App\Enums\Survey\SurveyQuestionType;
use Inertia\Inertia;
use Inertia\Response;

class SurveyBuilderController extends Controller
{
    public function index(Survey $survey): Response
    {
        $survey->load([
            'sections.questions.options',
            'questions.options',
        ]);

        return Inertia::render('admin/survey/Builder', [
            'survey' => [
                'id' => $survey->id,
                'title' => $survey->title,
                'status' => [
                    'value' => $survey->status->value,
                    'label' => $survey->status->label(),
                    'color' => $survey->status->color(),
                ],
            ],

            'sections' => SurveySectionResource::collection(
                $survey->sections
            ),

            'questions' => SurveyQuestionResource::collection(
                $survey->questions
            ),

            'typeOptions' => SurveyQuestionType::dropdown(),
        ]);
    }
}
