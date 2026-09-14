<?php

namespace App\Http\Resources\Interview;

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

            'interview' => $this->whenLoaded('interview', fn() => [
                'id' => $this->interview->id,
                'title' => $this->interview->title,
            ]),

            'asked_by' => $this->whenLoaded(
                'asker',
                fn () => [
                    'id' => $this->asker?->id,
                    'name' => $this->asker?->name,
                ],
            ),

            'answers' => InterviewAnswerResource::collection(
                $this->whenLoaded('answers'),
            ),
        ];
    }
}