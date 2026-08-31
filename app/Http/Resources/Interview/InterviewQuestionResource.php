<?php

namespace App\Http\Resources\Interview;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InterviewQuestionResource extends JsonResource
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
            'question' => $this->question,
            'order' => $this->order,

            'asked_by' => [
                'id' => $this->interviewer?->id,
                'username' => $this->interviewer?->username,
                'name' => $this->interviewer?->name,
                'avatar' => $this->interviewer?->avatar,
            ],

            'answers' => InterviewAnswerResource::collection(
                $this->whenLoaded('answers')
            ),
        ];
    }
}
