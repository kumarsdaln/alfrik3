<?php

namespace App\Actions\Survey;

use App\Models\Survey\SurveyQuestionOption;

class DeleteSurveyQuestionOption
{
    public function handle(
        SurveyQuestionOption $option,
    ): void {
        $option->delete();
    }
}