<?php

namespace App\Actions\Survey;

use App\Enums\Survey\SurveyResponseStatus;
use App\Models\Survey\SurveyResponse;

class SubmitSurveyResponse
{
    public function handle(
        SurveyResponse $response,
    ): SurveyResponse {
        $response->update([
            'status' => SurveyResponseStatus::Submitted,
            'submitted_at' => now(),
        ]);

        return $response->fresh();
    }
}