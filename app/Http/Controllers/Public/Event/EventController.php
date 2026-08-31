<?php

namespace App\Http\Controllers\Public\Event;

use App\Http\Controllers\Controller;
use App\Models\Event\Event;
use App\Models\Event\EventCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EventController extends Controller
{
    public function index(Request $request): Response
    {
        /*
    |--------------------------------------------------------------------------
    | Featured Event
    |--------------------------------------------------------------------------
    */

        $featured = Event::query()
            ->with('categories')
            ->where('status', 'published')
            ->where('visibility', 'public')
            ->where('end_date', '>=', now())
            ->orderBy('start_date')
            ->first();

        /*
    |--------------------------------------------------------------------------
    | Events Query
    |--------------------------------------------------------------------------
    */

        $query = Event::query()
            ->with('categories')
            ->where('status', 'published')
            ->where('visibility', 'public');

        /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();

            $query->where(function ($query) use ($search) {
                $query
                    ->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('location_name', 'like', "%{$search}%");
            });
        }

        /*
    |--------------------------------------------------------------------------
    | Category
    |--------------------------------------------------------------------------
    */

        if ($request->filled('category')) {
            $query->whereHas('categories', function ($query) use ($request) {
                $query->where(
                    'slug',
                    $request->string('category')->toString()
                );
            });
        }

        /*
    |--------------------------------------------------------------------------
    | Status
    |--------------------------------------------------------------------------
    */

        if ($request->input('status') === 'upcoming') {
            $query->where('start_date', '>=', now());
        }

        if ($request->input('status') === 'past') {
            $query->where('start_date', '<', now());
        }

        /*
    |--------------------------------------------------------------------------
    | Events
    |--------------------------------------------------------------------------
    */

        $events = $query
            ->orderBy('start_date')
            ->paginate(12)
            ->withQueryString();

        /*
    |--------------------------------------------------------------------------
    | Categories
    |--------------------------------------------------------------------------
    */

        $categories = EventCategory::query()
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'slug',
            ]);

        return Inertia::render('event/Index', [
            'events' => $events,

            'featured' => $featured,

            'categories' => $categories,

            'qfilters' => [
                'search' => $request->input('search', ''),
                'category' => $request->input('category', ''),
                'status' => $request->input('status', ''),
            ],
        ]);
    }

    public function show(Event $event): Response
    {
        abort_unless(
            $event->status === 'published',
            404
        );

        abort_unless(
            $event->visibility === 'public',
            404
        );

        /*
        |--------------------------------------------------------------------------
        | Event
        |--------------------------------------------------------------------------
        */

        $event->load([
            'categories',

            'sessions' => fn($query) => $query
                ->orderBy('position')
                ->orderBy('start_time'),

            'sessions.speakers',

            'tickets' => fn($query) => $query
                ->where('is_active', true)
                ->where('is_visible', true)
                ->orderBy('position'),

            'media' => fn($query) => $query
                ->orderBy('position'),

            'reviews' => fn($query) => $query
                ->with('user')
                ->latest(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Registration / Participants
        |--------------------------------------------------------------------------
        */

        $registrationsCount = $event->registrations()->count();

        $isRegistered = auth()->check()
            ? $event->registrations()
            ->where('user_id', auth()->id())
            ->exists()
            : false;

        $spotsLeft = $event->max_attendees
            ? max(
                0,
                $event->max_attendees - $registrationsCount
            )
            : null;

        /*
        |--------------------------------------------------------------------------
        | Participants
        |--------------------------------------------------------------------------
        |
        | We only expose registered users if the event's registration
        | relationship has a user relationship.
        |
        */

        $participants = $event->registrations()
            ->with('user')
            ->latest()
            ->take(24)
            ->get()
            ->map(fn($registration) => $registration->user)
            ->filter()
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Related Events
        |--------------------------------------------------------------------------
        */

        $related = Event::query()
            ->where('id', '!=', $event->id)
            ->where('status', 'published')
            ->where('visibility', 'public')
            ->where(function ($query) use ($event) {
                $query->whereHas('categories', function ($query) use ($event) {
                    $query->whereIn(
                        'event_categories.id',
                        $event->categories->pluck('id')
                    );
                });
            })
            ->with('categories')
            ->orderBy('start_date')
            ->take(3)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Review Stats
        |--------------------------------------------------------------------------
        */

        $reviewStats = [
            'average' => round(
                (float) $event->reviews()->avg('rating'),
                1
            ),

            'count' => $event->reviews()->count(),
        ];

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return Inertia::render('event/Show', [
            'event' => $event,

            'registration' => [
                'registrations_count' => $registrationsCount,
                'is_registered' => $isRegistered,
                'spots_left' => $spotsLeft,
            ],

            'participants' => $participants,

            'reviewStats' => $reviewStats,

            'related' => $related,
        ]);
    }
}
