<?php

namespace App\Actions\Survey;

use App\Models\Survey\Survey;

class UpdateSurvey
{
    public function handle(
        Survey $survey,
        array $data,
    ): Survey {
        $survey->update($data);

        return $survey->fresh();
    }
}
