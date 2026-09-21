<?php

namespace App\Actions\Survey;

use App\Models\Survey\SurveyAnswer;

class UpdateSurveyAnswer
{
    public function handle(
        SurveyAnswer $answer,
        array $data,
    ): SurveyAnswer {
        $answer->update($data);

        return $answer->fresh();
    }
}