<?php

namespace App\Http\Resources\Interview;

use App\Http\Resources\ContentParticipant\ParticipantResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InterviewQuestionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'question' => $this->question,
            'position' => $this->position,

            'interview' => $this->whenLoaded('interview', fn () => [
                'id' => $this->interview->id,
                'title' => $this->interview->title,
                'slug' => $this->interview->slug,
            ]),

            'asked_by' => new ParticipantResource(
                $this->whenLoaded('asker'),
            ),

            'answers' => InterviewAnswerResource::collection(
                $this->whenLoaded('answers'),
            ),
        ];
    }
}
