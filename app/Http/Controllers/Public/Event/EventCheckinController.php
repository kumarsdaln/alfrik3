<?php

namespace App\Http\Controllers\Public\Event;

use App\Http\Controllers\Controller;
use App\Models\Event\Event;
use App\Models\Event\EventCheckin;
use App\Models\Event\EventRegistration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EventCheckinController extends Controller
{
    /** Validate an attendee's QR token and record entry. */
    public function store(Request $request, string $slug): RedirectResponse
    {
        $validated = $request->validate([
            'qr_code' => ['required', 'string', 'max:255'],
            'gate' => ['nullable', 'string', 'max:100'],
            'device' => ['nullable', 'string', 'max:100'],
        ]);

        $staffUser = $request->user();

        $result = DB::transaction(function () use ($slug, $validated, $staffUser) {
            $event = Event::query()->where('slug', $slug)->lockForUpdate()->first();

            if (! $event) {
                abort(404);
            }

            $isCreator = (string) $event->created_by === (string) $staffUser->id;
            $isEventStaff = $event->participants()
                ->where('users.id', $staffUser->id)
                ->wherePivotIn('role', ['organizer', 'host'])
                ->exists();

            abort_unless($isCreator || $isEventStaff, 403, 'Only event organizers or hosts can check in attendees.');

            $registration = EventRegistration::query()
                ->where('event_id', $event->id)
                ->where('qr_code', $validated['qr_code'])
                ->lockForUpdate()
                ->first();

            if (! $registration) {
                throw ValidationException::withMessages([
                    'qr_code' => 'This QR code is invalid or does not belong to this event.',
                ]);
            }

            if ($this->enumValue($registration->status) !== 'confirmed') {
                throw ValidationException::withMessages([
                    'qr_code' => 'This registration is not confirmed and cannot be checked in.',
                ]);
            }

            $alreadyCheckedIn = EventCheckin::query()
                ->where('registration_id', $registration->id)
                ->where('type', 'entry')
                ->where('successful', true)
                ->exists();

            if ($alreadyCheckedIn) {
                throw ValidationException::withMessages([
                    'qr_code' => 'This attendee has already been checked in.',
                ]);
            }

            $checkin = EventCheckin::query()->create([
                'registration_id' => $registration->id,
                'checked_in_by' => $staffUser->id,
                'checked_in_at' => now(),
                'type' => 'entry',
                'gate' => $validated['gate'] ?? null,
                'device' => $validated['device'] ?? null,
                'successful' => true,
                'notes' => null,
            ]);

            return ['checkin' => $checkin, 'registration' => $registration];
        }, attempts: 3);

        return back()->with('success', 'Attendee checked in successfully.');
    }

    private function enumValue(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return $value instanceof \BackedEnum ? (string) $value->value : (string) $value;
    }
}
