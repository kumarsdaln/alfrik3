<?php

namespace App\Http\Resources\Research;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResearchInvitationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $status = match (true) {
            $this->accepted_at !== null => 'accepted',
            $this->expires_at->isPast() => 'expired',
            default => 'pending',
        };

        return [
            'id' => $this->id,

            'research_id' => $this->research_id,

            'invited_by' => $this->invited_by,

            'user_id' => $this->user_id,

            'email' => $this->email,

            'role' => [
                'value' => $this->role->value,
                'label' => $this->role->label(),
                'color' => $this->role->color(),
            ],

            'status' => $status,

            'token' => $this->token,

            'expires_at' => $this->expires_at?->toISOString(),
            'accepted_at' => $this->accepted_at?->toISOString(),

            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
