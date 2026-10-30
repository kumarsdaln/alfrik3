<?php

namespace App\Http\Requests\Event;

use App\Enums\Event\EventStatus;
use App\Enums\Event\EventType;
use App\Enums\Event\EventVisibility;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $event = $this->route('event');

        $eventId = is_object($event) ? $event->id : $event;

        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'slug' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('events', 'slug')->ignore($eventId),
            ],
            'description' => ['sometimes', 'nullable', 'string'],

            'event_type' => [
                'sometimes',
                'required',
                Rule::enum(EventType::class),
            ],
            'visibility' => [
                'sometimes',
                'required',
                Rule::enum(EventVisibility::class),
            ],

            'start_date' => ['sometimes', 'required', 'date'],
            'end_date' => [
                'sometimes',
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],

            'location_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'address' => ['sometimes', 'nullable', 'string', 'max:255'],
            'city' => ['sometimes', 'nullable', 'string', 'max:255'],
            'state' => ['sometimes', 'nullable', 'string', 'max:255'],
            'country' => ['sometimes', 'nullable', 'string', 'max:255'],
            'meeting_url' => ['sometimes', 'nullable', 'url', 'max:2048'],

            'max_attendees' => [
                'sometimes',
                'nullable',
                'integer',
                'min:1',
            ],

            'status' => [
                'sometimes',
                Rule::enum(EventStatus::class),
            ],
        ];
    }
}
