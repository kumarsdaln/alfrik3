<?php

namespace App\Actions\Survey;

use App\Models\Survey\SurveySection;

class UpdateSurveySection
{
    public function handle(
        SurveySection $section,
        array $data,
    ): SurveySection {
        $section->update($data);

        return $section->fresh();
    }
}
