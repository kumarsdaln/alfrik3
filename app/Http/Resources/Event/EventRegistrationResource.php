<?php

namespace App\Http\Resources\Event;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventRegistrationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'event_id' => $this->event_id,
            'ticket_id' => $this->ticket_id,
            'user_id' => $this->user_id,
            'registration_code' => $this->registration_code,
            'status' => $this->status,
            'qr_code' => $this->qr_code,

            'user' => $this->whenLoaded(
                'user',
                fn () => $this->user ? [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                ] : null,
            ),

            'ticket' => $this->whenLoaded(
                'ticket',
                fn () => $this->ticket ? [
                    'id' => $this->ticket->id,
                    'name' => $this->ticket->name,
                    'price' => $this->ticket->price,
                    'currency' => $this->ticket->currency,
                ] : null,
            ),

            'checkins' => EventCheckinResource::collection(
                $this->whenLoaded('checkins'),
            ),

            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}