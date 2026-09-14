<?php

namespace App\Http\Resources\Interview;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InterviewAnswerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'answer' => $this->answer,

            'answered_by' => $this->whenLoaded(
                'answerer',
                fn () => [
                    'id' => $this->answerer?->id,
                    'name' => $this->answerer?->name,
                ],
            ),
        ];
    }
}