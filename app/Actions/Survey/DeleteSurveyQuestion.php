<?php

namespace App\Actions\Survey;

use App\Models\Survey\SurveyQuestion;

class DeleteSurveyQuestion
{
    public function handle(SurveyQuestion $question): void
    {
        $question->delete();
    }
}