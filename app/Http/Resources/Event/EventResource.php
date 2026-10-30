<?php

namespace App\Http\Resources\Event;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\MediaResource;
use App\Http\Resources\TagResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,

            'event_type' => $this->event_type?->option(),

            'visibility' => $this->visibility?->option(),

            'status' => $this->status?->option(),

            'start_date' => $this->start_date?->toISOString(),
            'end_date' => $this->end_date?->toISOString(),

            'location_name' => $this->location_name,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->country,
            'meeting_url' => $this->meeting_url,
            'max_attendees' => $this->max_attendees,
            'created_by' => $this->created_by,

            'creator' => $this->whenLoaded(
                'creator',
                fn () => $this->creator ? [
                    'id' => $this->creator->id,
                    'name' => $this->creator->name,
                    'username' => $this->creator->username,
                    'avatar' => $this->creator->avatar,
                ] : null,
            ),

            'categories' => CategoryResource::collection($this->whenLoaded(
                'categories'
            )),

            'tags' => TagResource::collection($this->whenLoaded(
                'tags'
            )),

            'sessions' => EventSessionResource::collection(
                $this->whenLoaded('sessions'),
            ),

            'participants' => EventParticipantResource::collection(
                $this->whenLoaded('participants'),
            ),

            'tickets' => EventTicketResource::collection(
                $this->whenLoaded('tickets'),
            ),

            'registrations' => EventRegistrationResource::collection(
                $this->whenLoaded('registrations'),
            ),

            'reviews' => EventReviewResource::collection(
                $this->whenLoaded('reviews'),
            ),

            'media' => MediaResource::collection($this->whenLoaded('media')),

            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}