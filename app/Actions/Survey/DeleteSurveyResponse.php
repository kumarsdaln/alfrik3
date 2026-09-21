<?php

namespace App\Actions\Survey;

use App\Models\Survey\SurveyResponse;

class DeleteSurveyResponse
{
    public function handle(
        SurveyResponse $response,
    ): void {
        $response->delete();
    }
}