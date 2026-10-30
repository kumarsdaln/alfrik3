<?php

namespace App\Http\Resources\Survey;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SurveyResource extends JsonResource
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

            'research_id' => $this->research_id,

            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,

            'status' => $this->status->option(),

            'anonymous' => $this->anonymous,
            'multiple_responses' => $this->multiple_responses,
            'featured' => $this->featured,

            'starts_at' => $this->starts_at?->toISOString(),
            'ends_at' => $this->ends_at?->toISOString(),

            'questions_count' => $this->questions_count,
            'response_count' => $this->response_count,

            /*
            |--------------------------------------------------------------------------
            | Relationships
            |--------------------------------------------------------------------------
            */

            'sections' => SurveySectionResource::collection(
                $this->whenLoaded('sections')
            ),

            'questions' => SurveyQuestionResource::collection(
                $this->whenLoaded('questions')
            ),

            'responses' => SurveyResponseResource::collection(
                $this->whenLoaded('responses')
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