<?php

namespace App\Actions\Survey;

use App\Models\Survey\Survey;

class DeleteSurvey
{
    public function handle(
        Survey $survey,
    ): void {
        $survey->delete();
    }
}
