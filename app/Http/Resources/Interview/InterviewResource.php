<?php

namespace App\Http\Resources\Interview;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InterviewResource extends JsonResource
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
            'thumbnail' => $this->thumbnail,
            'interview_type' => $this->interview_type->label(),
            'duration' => $this->formatted_duration,
            'action_label' => $this->action_label,
            'published_at' => $this->published_at,
            
            'description' => $this->description,
            
            'participants' => InterviewParticipantResource::collection(
                $this->whenLoaded('participants')
            ),
            'questions' => InterviewQuestionResource::collection(
                $this->whenLoaded('questions')
            ),

            'media' => InterviewMediaResource::collection(
                $this->whenLoaded('media')
            ),
        ];
    }
}
