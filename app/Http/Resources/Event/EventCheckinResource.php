<?php

namespace App\Http\Resources\Event;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventCheckinResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'registration_id' => $this->registration_id,
            'checked_in_by' => $this->checked_in_by,
            'checked_in_at' => $this->checked_in_at?->toISOString(),
            'type' => $this->type,
            'gate' => $this->gate,
            'device' => $this->device,
            'successful' => $this->successful,
            'notes' => $this->notes,

            'checked_in_by_user' => $this->whenLoaded(
                'checkedInBy',
                fn () => $this->checkedInBy ? [
                    'id' => $this->checkedInBy->id,
                    'name' => $this->checkedInBy->name,
                ] : null,
            ),

            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}