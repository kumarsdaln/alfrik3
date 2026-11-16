<?php

namespace App\Http\Resources\Interview;

use App\Http\Resources\ContentParticipant\ParticipantResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InterviewAnswerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'answer' => $this->answer,
            'position' => $this->position,

            'answered_by' => new ParticipantResource(
                $this->whenLoaded('answerer'),
            ),
        ];
    }
}