<?php

namespace App\Http\Requests\Event;

use App\Enums\Event\EventStatus;
use App\Enums\Event\EventType;
use App\Enums\Event\EventVisibility;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Replace with your event-management authorization policy.
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('events', 'slug'),
            ],
            'description' => ['nullable', 'string'],

            'event_type' => [
                'required',
                Rule::enum(EventType::class),
            ],
            'visibility' => [
                'required',
                Rule::enum(EventVisibility::class),
            ],

            'start_date' => ['required', 'date'],
            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],

            'location_name' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'meeting_url' => ['nullable', 'url', 'max:2048'],

            'max_attendees' => [
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
