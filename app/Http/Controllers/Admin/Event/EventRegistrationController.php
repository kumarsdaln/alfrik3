<?php

namespace App\Http\Controllers\Admin\Event;

use App\Http\Controllers\Controller;
use App\Models\Event\EventCheckin;
use App\Models\Event\EventRegistration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class EventRegistrationController extends Controller
{
    /**
     * Display event registrations.
     */
    public function index(Request $request): Response
    {
        $this->authorize('viewAny', EventRegistration::class);

        $filters = $request->only(['search', 'status']);

        $registrations = EventRegistration::query()
            ->with([
                'event:id,title',
                'ticket:id,name,price,currency',
                'user:id,name,email',
            ])
            ->when(
                $request->filled('search'),
                function ($query) use ($request) {
                    $search = trim(
                        $request->string('search')->toString()
                    );

                    $query->where(function ($query) use ($search) {
                        $query->where(
                            'registration_code',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhereHas('user', function ($query) use ($search) {
                            $query->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        })
                        ->orWhereHas('event', function ($query) use ($search) {
                            $query->where('title', 'like', "%{$search}%");
                        });
                    });
                }
            )
            ->when(
                $request->filled('status'),
                fn ($query) => $query->where(
                    'status',
                    $request->input('status')
                )
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('admin/event/registration/Index', [
            'registrations' => $registrations,
            'filters' => $filters,
        ]);
    }

    /**
     * Display a single registration and its check-in history.
     */
    public function show(
        EventRegistration $registration
    ): Response {
        $this->authorize('view', $registration);

        $registration->load([
            'event:id,title,slug,event_type,start_date,end_date,location_name,city,state,country',
            'ticket:id,event_id,name,price,currency',
            'user:id,name,email',
            'checkins' => function ($query) {
                $query
                    ->with('checkedInBy:id,name')
                    ->reorder('checked_in_at', 'desc')
                    ->orderByDesc('id');
            },
        ]);

        return Inertia::render(
            'admin/event/registration/Show',
            [
                'registration' => $registration,
            ]
        );
    }

    /**
     * Display the QR-code scanner.
     */
    public function scanner(): Response
    {
        $this->authorize('viewAny', EventRegistration::class);

        return Inertia::render(
            'admin/event/registration/Scanner'
        );
    }

    /**
     * Find a registration using its QR-code value.
     */
    public function scan(Request $request): RedirectResponse
    {
        $this->authorize('viewAny', EventRegistration::class);

        $validated = $request->validate([
            'qr_code' => ['required', 'string', 'max:255'],
        ]);

        $qrCode = trim($validated['qr_code']);

        $registration = EventRegistration::query()
            ->where('qr_code', $qrCode)
            ->first();

        if (! $registration) {
            throw ValidationException::withMessages([
                'qr_code' => __(
                    'No registration was found for this QR code.'
                ),
            ]);
        }

        $this->authorize('view', $registration);

        if ($registration->status !== 'confirmed') {
            $message = match ($registration->status) {
                'pending' => __('This registration is still pending.'),
                'cancelled' => __('This registration has been cancelled.'),
                'refunded' => __('This registration has been refunded.'),
                default => __(
                    'This registration is not eligible for check-in.'
                ),
            };

            throw ValidationException::withMessages([
                'qr_code' => $message,
            ]);
        }

        return to_route(
            'admin.events.registrations.show',
            $registration
        );
    }

    /**
     * Record an attendee's entry or exit.
     */
    public function checkin(
        Request $request,
        EventRegistration $registration,
    ): RedirectResponse {
        $this->authorize('view', $registration);

        $validated = $request->validate([
            'type' => ['required', 'in:entry,exit'],
            'gate' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        DB::transaction(function () use (
            $registration,
            $validated,
            $request
        ) {
            // Serialize check-in operations for this registration.
            $lockedRegistration = EventRegistration::query()
                ->whereKey($registration->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedRegistration->status !== 'confirmed') {
                throw ValidationException::withMessages([
                    'registration' => __(
                        'Only confirmed registrations can check in.'
                    ),
                ]);
            }

            $lastSuccessfulCheckin = $lockedRegistration->checkins()
                ->where('successful', true)
                ->orderByDesc('checked_in_at')
                ->orderByDesc('id')
                ->first();

            $type = $validated['type'];

            if (
                $type === 'entry'
                && $lastSuccessfulCheckin?->type === 'entry'
            ) {
                throw ValidationException::withMessages([
                    'registration' => __(
                        'This attendee is already checked in.'
                    ),
                ]);
            }

            if (
                $type === 'exit'
                && $lastSuccessfulCheckin?->type !== 'entry'
            ) {
                throw ValidationException::withMessages([
                    'registration' => __(
                        'The attendee must check in before checking out.'
                    ),
                ]);
            }

            $checkin = new EventCheckin();

            $checkin->registration_id = $lockedRegistration->id;
            $checkin->checked_in_by = $request->user()->id;
            $checkin->checked_in_at = now();
            $checkin->type = $type;
            $checkin->gate = $validated['gate'] ?? null;
            $checkin->notes = $validated['notes'] ?? null;
            $checkin->successful = true;

            $checkin->save();
        });

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('Check-in recorded successfully.'),
        ]);

        return back();
    }
}