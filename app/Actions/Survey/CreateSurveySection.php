<?php

namespace App\Actions\Survey;

use App\Models\Survey\Survey;
use App\Models\Survey\SurveySection;

class CreateSurveySection
{
    public function handle(
        Survey $survey,
        array $data,
    ): SurveySection {
        $data['survey_id'] = $survey->id;

        return SurveySection::create($data);
    }
}
