<?php

namespace App\Http\Controllers\Public\Event;

use App\Http\Controllers\Controller;
use App\Models\Event\Event;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EventController extends Controller
{
    public function index(Request $request): Response
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:150'],
            'type' => ['nullable', 'in:online,offline,hybrid'],
            'period' => ['nullable', 'in:upcoming,past,all'],
        ]);

        $period = $filters['period'] ?? 'upcoming';

        $events = Event::query()
            ->published()
            ->public()
            ->when(
                ! empty($filters['search']),
                function ($query) use ($filters) {
                    $search = trim($filters['search']);

                    $query->where(function ($query) use ($search) {
                        $query
                            ->where('title', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%")
                            ->orWhere('location_name', 'like', "%{$search}%")
                            ->orWhere('city', 'like', "%{$search}%");
                    });
                }
            )
            ->when(
                ! empty($filters['type']),
                fn($query) => $query->where(
                    'event_type',
                    $filters['type']
                )
            )
            ->when(
                $period === 'upcoming',
                fn($query) => $query->upcoming()
            )
            ->when(
                $period === 'past',
                fn($query) => $query->past()
            )
            ->withCount([
                'registrations as confirmed_registrations_count' =>
                fn($query) => $query->where('status', 'confirmed'),
            ])
            ->orderBy('start_date', $period === 'past' ? 'desc' : 'asc')
            ->paginate(12)
            ->withQueryString()
            ->through(fn(Event $event) => [
                'id' => $event->id,
                'title' => $event->title,
                'slug' => $event->slug,
                'description' => $event->description,
                'event_type' => $event->event_type->value,
                'start_date' => $event->start_date?->toIso8601String(),
                'end_date' => $event->end_date?->toIso8601String(),
                'location_name' => $event->location_name,
                'city' => $event->city,
                'state' => $event->state,
                'country' => $event->country,
                'max_attendees' => $event->max_attendees,
                'confirmed_registrations_count' =>
                $event->confirmed_registrations_count,
            ]);

        return Inertia::render('event/Index', [
            'events' => $events,
            'filters' => [
                'search' => $filters['search'] ?? '',
                'type' => $filters['type'] ?? '',
                'period' => $period,
            ],
        ]);
    }

    public function show(string $slug): Response
    {
        $event = Event::query()
            ->published()
            ->public()
            ->where('slug', $slug)
            ->with([
                'creator:id,name',

            'participants' => fn($query) => $query
                ->whereIn('role', ['organizer', 'host', 'speaker'])
                ->with('user:id,name'),

                'sessions' => fn($query) => $query
                    ->orderBy('position')
                    ->with('speakers:id,name'),

                'tickets' => fn($query) => $query
                    ->where('is_visible', true)
                    ->where('is_active', true)
                    ->orderBy('position'),
            ])
            ->withCount([
                'registrations as confirmed_registrations_count' =>
                fn($query) => $query->where('status', 'confirmed'),
                'reviews',
            ])
            ->withAvg('reviews', 'rating')
            ->firstOrFail();

        $user = request()->user();

        $registration = $user
            ? $event->registrations()
            ->where('user_id', $user->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->with('ticket')
            ->latest('id')
            ->first()
            : null;

        $relatedEvents = Event::query()
            ->published()
            ->public()
            ->whereKeyNot($event->getKey())
            ->where('start_date', '>=', now())
            ->when(
                $event->event_type,
                fn($query) => $query->where(
                    'event_type',
                    $event->event_type->value
                )
            )
            ->withCount([
                'registrations as confirmed_registrations_count' =>
                fn($query) => $query->where('status', 'confirmed'),
            ])
            ->orderBy('start_date')
            ->limit(4)
            ->get()
            ->map(fn(Event $related) => [
                'id' => $related->id,
                'title' => $related->title,
                'slug' => $related->slug,
                'description' => $related->description,
                'event_type' => $related->event_type->value,
                'start_date' => $related->start_date?->toIso8601String(),
                'end_date' => $related->end_date?->toIso8601String(),
                'location_name' => $related->location_name,
                'city' => $related->city,
                'state' => $related->state,
                'country' => $related->country,
                'confirmed_registrations_count' =>
                $related->confirmed_registrations_count,
            ]);

        return Inertia::render('event/Show', [
            'event' => [
                'id' => $event->id,
                'title' => $event->title,
                'slug' => $event->slug,
                'description' => $event->description,
                'event_type' => $event->event_type->value,
                'start_date' => $event->start_date?->toIso8601String(),
                'end_date' => $event->end_date?->toIso8601String(),
                'location_name' => $event->location_name,
                'address' => $event->address,
                'city' => $event->city,
                'state' => $event->state,
                'country' => $event->country,
                'meeting_url' => $event->meeting_url,
                'max_attendees' => $event->max_attendees,
                'confirmed_registrations_count' =>
                $event->confirmed_registrations_count,
                'creator' => $event->creator,
                'participants' => $event->participants,
                'sessions' => $event->sessions,
                'tickets' => $event->tickets,
                'reviews_count' => $event->reviews_count,
                'average_rating' => round((float) ($event->reviews_avg_rating ?? 0), 1),
            ],
            'registration' => $registration,
            'related' => $relatedEvents,
            'isAuthenticated' => $user !== null,
        ]);
    }
}
