<?php

namespace App\Http\Controllers\Public\Event;

use App\Http\Controllers\Controller;
use App\Models\Event\Event;
use App\Models\Event\EventRegistration;
use App\Models\Event\EventTicket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class EventRegistrationController extends Controller
{
    /** Register the authenticated user for a ticket. */

    public function store(Request $request, Event $event): RedirectResponse
    {
        $validated = $request->validate([
            'ticket_id' => ['required', 'integer'],
        ]);

        $user = $request->user();

        $registration = DB::transaction(function () use ($event, $validated, $user) {
            // Lock the event row to prevent concurrent registrations exceeding capacity.
            $event = Event::query()
                ->whereKey($event->id)
                ->lockForUpdate()
                ->first();

            if (
                ! $event
                || $this->enumValue($event->status) !== 'published'
                || $this->enumValue($event->visibility) !== 'public'
            ) {
                throw ValidationException::withMessages([
                    'event' => 'This event is not available for registration.',
                ]);
            }

            // Do not accept registrations after the event has ended.
            $eventEnd = $event->end_date ?? $event->start_date;

            if ($eventEnd && $eventEnd->isPast()) {
                throw ValidationException::withMessages([
                    'event' => 'Registration for this event has ended.',
                ]);
            }

            $ticket = EventTicket::query()
                ->where('event_id', $event->id)
                ->whereKey($validated['ticket_id'])
                ->lockForUpdate()
                ->first();

            if (! $ticket || ! $ticket->is_active || ! $ticket->is_visible) {
                throw ValidationException::withMessages([
                    'ticket_id' => 'This ticket is not available.',
                ]);
            }

            $now = now();

            if ($ticket->sales_start && $now->lt($ticket->sales_start)) {
                throw ValidationException::withMessages([
                    'ticket_id' => 'Ticket sales have not started yet.',
                ]);
            }

            if ($ticket->sales_end && $now->gt($ticket->sales_end)) {
                throw ValidationException::withMessages([
                    'ticket_id' => 'Ticket sales for this ticket have ended.',
                ]);
            }

            $activeStatuses = ['pending', 'confirmed'];

            $existing = EventRegistration::query()
                ->where('event_id', $event->id)
                ->where('user_id', $user->id)
                ->whereIn('status', $activeStatuses)
                ->lockForUpdate()
                ->first();

            if ($existing) {
                throw ValidationException::withMessages([
                    'ticket_id' => 'You already have a registration for this event.',
                ]);
            }

            // Enforce the overall event capacity.
            if ($event->max_attendees !== null) {
                $eventRegistrationCount = EventRegistration::query()
                    ->where('event_id', $event->id)
                    ->whereIn('status', $activeStatuses)
                    ->count();

                if ($eventRegistrationCount >= $event->max_attendees) {
                    throw ValidationException::withMessages([
                        'ticket_id' => 'This event has reached its attendee capacity.',
                    ]);
                }
            }

            // Enforce the capacity of this specific ticket.
            if ($ticket->capacity !== null) {
                $ticketRegistrationCount = EventRegistration::query()
                    ->where('ticket_id', $ticket->id)
                    ->whereIn('status', $activeStatuses)
                    ->count();

                if ($ticketRegistrationCount >= $ticket->capacity) {
                    throw ValidationException::withMessages([
                        'ticket_id' => 'This ticket is sold out.',
                    ]);
                }
            }

            $price = (float) ($ticket->price ?? 0);
            $status = $price <= 0 ? 'confirmed' : 'pending';

            return EventRegistration::query()->create([
                'event_id' => $event->id,
                'ticket_id' => $ticket->id,
                'user_id' => $user->id,
                'registration_code' => $this->generateRegistrationCode(),
                'qr_code' => $status === 'confirmed'
                    ? $this->generateQrToken()
                    : null,
                'status' => $status,
            ]);
        }, attempts: 3);

        return redirect()
            ->route('events.registrations.show', [
                'registration' => $registration->id,
            ])
            ->with(
                $this->enumValue($registration->status) === 'pending'
                    ? 'info'
                    : 'success',
                $this->enumValue($registration->status) === 'pending'
                    ? 'Your registration is pending payment. Complete payment to confirm your place.'
                    : 'You are registered for this event.'
            );
    }


    /** Show a registration belonging to the currently authenticated user. */
    public function show(
        Request $request,
        EventRegistration $registration
    ): InertiaResponse {
        abort_unless(
            (string) $registration->user_id === (string) $request->user()->id,
            404
        );

        $registration->load(['event', 'ticket']);

        $checkedInAt = $registration->checkins()
            ->where('type', 'entry')
            ->where('successful', true)
            ->latest('checked_in_at')
            ->value('checked_in_at');

        return Inertia::render('events/registration/Show', [
            'event' => [
                'id' => $registration->event->id,
                'title' => $registration->event->title,
                'slug' => $registration->event->slug,
                'start_date' => $registration->event->start_date,
                'end_date' => $registration->event->end_date,
                'event_type' => $this->enumValue($registration->event->event_type),
                'location_name' => $registration->event->location_name,
                'city' => $registration->event->city,
                'state' => $registration->event->state,
                'country' => $registration->event->country,
            ],
            'registration' => [
                'id' => $registration->id,
                'registration_code' => $registration->registration_code,
                'status' => $this->enumValue($registration->status),
                'qr_code' => $registration->qr_code,
                'created_at' => $registration->created_at,
                'checked_in_at' => $checkedInAt,
            ],
            'ticket' => $registration->ticket ? [
                'id' => $registration->ticket->id,
                'name' => $registration->ticket->name,
                'price' => $registration->ticket->price,
                'currency' => $registration->ticket->currency,
            ] : null,
        ]);
    }

    private function generateRegistrationCode(): string
    {
        do {
            $code = 'EVT-' . Str::upper(Str::random(10));
        } while (EventRegistration::query()->where('registration_code', $code)->exists());

        return $code;
    }

    private function generateQrToken(): string
    {
        do {
            $token = Str::random(64);
        } while (EventRegistration::query()->where('qr_code', $token)->exists());

        return $token;
    }

    private function enumValue(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return $value instanceof \BackedEnum ? (string) $value->value : (string) $value;
    }
}
