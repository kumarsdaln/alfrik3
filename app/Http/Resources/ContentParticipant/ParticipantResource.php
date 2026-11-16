<?php

namespace App\Http\Resources\ContentParticipant;

use App\Http\Resources\ContentParticipant\ParticipantRoleResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParticipantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => $this->whenLoaded('user', fn () => new UserResource($this->user)),
            'role' => $this->whenLoaded('role', fn () => new ParticipantRoleResource($this->role)),
            'position' => $this->position,
            'is_primary' => $this->is_primary,
            'contribution' => $this->contribution,

            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
