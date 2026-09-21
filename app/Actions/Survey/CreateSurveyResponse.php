<?php

namespace App\Actions\Survey;

use App\Enums\Survey\SurveyResponseStatus;
use App\Models\Survey\Survey;
use App\Models\Survey\SurveyResponse;

class CreateSurveyResponse
{
    public function handle(
        Survey $survey,
        array $data,
    ): SurveyResponse {
        $data['survey_id'] = $survey->id;

        $data['status'] ??= SurveyResponseStatus::InProgress;

        $data['started_at'] ??= now();

        return SurveyResponse::create($data);
    }
}
