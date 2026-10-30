<?php

namespace App\Http\Resources\Survey;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SurveyQuestionResource extends JsonResource
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
            'section_id' => $this->section_id,

            'question' => $this->question,
            'description' => $this->description,

            'type' => $this->type->option(),

            'required' => $this->required,

            'position' => $this->position,

            'settings' => $this->settings,

            /*
            |--------------------------------------------------------------------------
            | Options
            |--------------------------------------------------------------------------
            */

            'options' => SurveyQuestionOptionResource::collection(
                $this->whenLoaded('options')
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