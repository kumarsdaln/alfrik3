<?php

namespace App\Actions\Survey;

use App\Models\Survey\SurveyQuestionOption;

class UpdateSurveyQuestionOption
{
    public function handle(
        SurveyQuestionOption $option,
        array $data,
    ): SurveyQuestionOption {
        $option->update($data);

        return $option->fresh();
    }
}