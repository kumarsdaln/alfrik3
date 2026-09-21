<?php

namespace App\Actions\Survey;

use App\Models\Survey\Survey;
use App\Models\Survey\SurveyQuestion;

class CreateSurveyQuestion
{
    public function handle(
        Survey $survey,
        array $data,
    ): SurveyQuestion {
        $data['survey_id'] = $survey->id;

        if (! isset($data['position'])) {
            $data['position'] = $survey->questions()->count();
        }

        return SurveyQuestion::create($data);
    }
}
