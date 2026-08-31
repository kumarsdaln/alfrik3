<?php

namespace App\Http\Resources\Interview;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InterviewAnswerResource extends JsonResource
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
            'answer' => $this->answer,

            'answered_by' => [
                'id' => $this->answeredBy?->id,
                'username' => $this->answeredBy?->username,
                'name' => $this->answeredBy?->name,
                'avatar' => $this->answeredBy?->avatar,
            ],
        ];
    }
}
