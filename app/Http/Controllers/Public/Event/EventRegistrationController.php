<?php

namespace App\Http\Controllers\Public\Event;

use App\Http\Controllers\Controller;
use App\Models\Event\Event;
use App\Models\Event\EventRegistration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class EventRegistrationController extends Controller
{
    public function create(Event $event): Response
    {
        abort_unless(
            $event->status === 'published',
            404
        );

        abort_unless(
            $event->visibility === 'public',
            404
        );

        $event->load([
            'tickets' => fn ($query) => $query
                ->where('is_active', true)
                ->where('is_visible', true)
                ->orderBy('position'),
        ]);

        return Inertia::render('Event/Register', [
            'event' => $event,
        ]);
    }

    public function store(
        Request $request,
        Event $event
    ): RedirectResponse {
        $validated = $request->validate([
            'ticket_id' => [
                'required',
                'integer',
                'exists:event_tickets,id',
            ],
        ]);

        abort_unless(
            $event->status === 'published',
            404
        );

        abort_unless(
            $event->visibility === 'public',
            404
        );

        $user = $request->user();

        abort_unless($user, 401);

        $registration = DB::transaction(function () use (
            $event,
            $user,
            $validated
        ) {
            $ticket = $event->tickets()
                ->whereKey($validated['ticket_id'])
                ->where('is_active', true)
                ->where('is_visible', true)
                ->lockForUpdate()
                ->firstOrFail();

            $now = now();

            if (
                $ticket->sales_start &&
                $ticket->sales_start->isFuture()
            ) {
                abort(
                    422,
                    'Ticket sales have not started yet.'
                );
            }

            if (
                $ticket->sales_end &&
                $ticket->sales_end->isPast()
            ) {
                abort(
                    422,
                    'Ticket sales have ended.'
                );
            }

            $alreadyRegistered = $event->registrations()
                ->where('user_id', $user->id)
                ->whereIn('status', [
                    'pending',
                    'confirmed',
                ])
                ->exists();

            if ($alreadyRegistered) {
                abort(
                    422,
                    'You are already registered for this event.'
                );
            }

            if ($ticket->capacity !== null) {
                $registeredCount = $ticket->registrations()
                    ->whereIn('status', [
                        'pending',
                        'confirmed',
                    ])
                    ->count();

                if ($registeredCount >= $ticket->capacity) {
                    abort(
                        422,
                        'This ticket is sold out.'
                    );
                }
            }

            if ($event->max_attendees !== null) {
                $eventRegistrationCount = $event->registrations()
                    ->whereIn('status', [
                        'pending',
                        'confirmed',
                    ])
                    ->count();

                if (
                    $eventRegistrationCount >=
                    $event->max_attendees
                ) {
                    abort(
                        422,
                        'This event is full.'
                    );
                }
            }

            $isFree = (float) $ticket->price <= 0;

            return EventRegistration::create([
                'event_id' => $event->id,
                'ticket_id' => $ticket->id,
                'user_id' => $user->id,

                'registration_code' =>
                    $this->generateRegistrationCode(),

                'qr_code' => Str::uuid()->toString(),

                'status' => $isFree
                    ? 'confirmed'
                    : 'pending',
            ]);
        });

        return to_route(
            'events.registrations.show',
            $registration
        )->with(
            'success',
            $registration->status === 'confirmed'
                ? 'Your registration is confirmed.'
                : 'Your registration has been created and is awaiting payment.'
        );
    }

    public function show(
        EventRegistration $registration
    ): Response {
        abort_unless(
            $registration->user_id === auth()->id(),
            403
        );

        $registration->load([
            'event',
            'ticket',
        ]);

        return Inertia::render(
            'Event/Registration/Show',
            [
                'registration' => $registration,
            ]
        );
    }

    public function cancel(
        EventRegistration $registration
    ): RedirectResponse {
        abort_unless(
            $registration->user_id === auth()->id(),
            403
        );

        if ($registration->status !== 'confirmed') {
            return back()->withErrors([
                'registration' =>
                    'This registration cannot be cancelled.',
            ]);
        }

        $registration->update([
            'status' => 'cancelled',
        ]);

        return back()->with(
            'success',
            'Registration cancelled successfully.'
        );
    }

    private function generateRegistrationCode(): string
    {
        do {
            $code = 'ALF-' . strtoupper(
                Str::random(8)
            );
        } while (
            EventRegistration::where(
                'registration_code',
                $code
            )->exists()
        );

        return $code;
    }
}