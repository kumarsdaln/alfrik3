<?php

namespace App\Actions\Survey;

use App\Models\Survey\SurveyQuestion;

class UpdateSurveyQuestion
{
    public function handle(
        SurveyQuestion $question,
        array $data,
    ): SurveyQuestion {
        $question->update($data);

        return $question->fresh();
    }
}
