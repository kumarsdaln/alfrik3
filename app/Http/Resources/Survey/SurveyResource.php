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
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,

            'questions_count' => $this->questions_count ?? 0,
            'responses_count' => $this->responses_count ?? 0,

            'published_at' => $this->published_at,
            'closes_at' => $this->closes_at,
            'created_at' => $this->created_at,
        ];
    }
}