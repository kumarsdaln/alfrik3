<?php

namespace App\Actions\Survey;

use App\Models\Survey\SurveyQuestion;
use App\Models\Survey\SurveyQuestionOption;

class CreateSurveyQuestionOption
{
    public function handle(
        SurveyQuestion $question,
        array $data,
    ): SurveyQuestionOption {
        $data['question_id'] = $question->id;

        if (! isset($data['position'])) {
            $data['position'] = $question->options()->count();
        }

        return SurveyQuestionOption::create($data);
    }
}