<?php

namespace App\Http\Resources\Survey;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SurveyResponseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'survey_id' => $this->survey_id,
            'user_id' => $this->user_id,

            'respondent_name' => $this->respondent_name,
            'respondent_email' => $this->respondent_email,

            'started_at' => $this->started_at?->toISOString(),
            'submitted_at' => $this->submitted_at?->toISOString(),

            'status' => $this->status->option(),

            /*
            |--------------------------------------------------------------------------
            | Answers
            |--------------------------------------------------------------------------
            */

            'answers' => SurveyAnswerResource::collection(
                $this->whenLoaded('answers')
            ),

            /*
            |--------------------------------------------------------------------------
            | Timestamps
            |--------------------------------------------------------------------------
            */

            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}