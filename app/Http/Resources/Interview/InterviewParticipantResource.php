<?php

namespace App\Http\Resources\Interview;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InterviewParticipantResource extends JsonResource
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
            'role' => $this->role,
            'user' => [
                'id' => $this->user?->id,
                'external_id' => $this->user?->external_id,
                'name' => $this->user?->name,
                'avatar' => $this->user?->avatar,
            ],
        ];
    }
}
