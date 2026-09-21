<?php

namespace App\Actions\Survey;

use App\Models\Survey\SurveySection;

class DeleteSurveySection
{
    public function handle(
        SurveySection $section,
    ): void {
        $section->delete();
    }
}