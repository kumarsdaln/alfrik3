<?php

namespace App\Actions\Survey;

use App\Models\Survey\SurveyAnswer;
use App\Models\Survey\SurveyResponse;

class CreateSurveyAnswer
{
    public function handle(
        SurveyResponse $response,
        array $data,
    ): SurveyAnswer {
        $data['response_id'] = $response->id;

        return SurveyAnswer::updateOrCreate(
            [
                'response_id' => $response->id,
                'question_id' => $data['question_id'],
            ],
            $data
        );
    }
}