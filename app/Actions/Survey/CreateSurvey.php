<?php

namespace App\Actions\Survey;

use App\Models\Survey\Survey;

class CreateSurvey
{
    public function handle(array $data): Survey
    {
        return Survey::create($data);
    }
}
