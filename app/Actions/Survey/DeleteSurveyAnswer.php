<?php

namespace App\Actions\Survey;

use App\Models\Survey\SurveyAnswer;

class DeleteSurveyAnswer
{
    public function handle(
        SurveyAnswer $answer,
    ): void {
        $answer->delete();
    }
}