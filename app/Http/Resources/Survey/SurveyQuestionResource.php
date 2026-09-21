<?php

namespace App\Http\Resources\Survey;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SurveyQuestionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'survey_id' => $this->survey_id,
            'section_id' => $this->section_id,

            'section' => $this->whenLoaded(
                'section',
                fn () => [
                    'id' => $this->section->id,
                    'title' => $this->section->title,
                ]
            ),

            'question' => $this->question,
            'description' => $this->description,

            'type' => [
                'value' => $this->type->value,
                'label' => $this->type->label(),
                'color' => $this->type->color(),
            ],

            'category' => $this->category,
            'required' => $this->required,
            'position' => $this->position,
            'settings' => $this->settings,

            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}