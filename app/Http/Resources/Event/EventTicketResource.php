<?php

namespace App\Http\Resources\Event;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventTicketResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'event_id' => $this->event_id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'currency' => $this->currency,
            'capacity' => $this->capacity,
            'sales_start_at' => $this->sales_start_at?->toISOString(),
            'sales_end_at' => $this->sales_end_at?->toISOString(),
            'is_visible' => $this->is_visible,
            'is_active' => $this->is_active,
            'position' => $this->position,

            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}