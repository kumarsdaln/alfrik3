<?php

namespace App\Http\Controllers\Admin\Event;

use App\Http\Controllers\Controller;
use App\Models\Event\Event;
use App\Models\Event\EventCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EventController extends Controller
{
    /**
     * Display all events.
     */
    public function index(Request $request): Response
{
    /*
    |--------------------------------------------------------------------------
    | Events Query
    |--------------------------------------------------------------------------
    */

    $query = Event::query()
        ->with('categories')
        ->withCount('registrations');

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
        $category = $request->string('category')->toString();

        $query->whereHas('categories', function ($query) use ($category) {
            $query->where('slug', $category);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Status
    |--------------------------------------------------------------------------
    */

    if ($request->filled('status')) {
        $query->where(
            'status',
            $request->string('status')->toString()
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Events
    |--------------------------------------------------------------------------
    */

    $events = $query
        ->orderBy('start_date')
        ->paginate(1)
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

    /*
    |--------------------------------------------------------------------------
    | Statistics
    |--------------------------------------------------------------------------
    */

    $stats = [
        'total' => Event::query()->count(),

        'published' => Event::query()
            ->where('status', 'published')
            ->count(),

        'upcoming' => Event::query()
            ->where('start_date', '>=', now())
            ->where('status', 'published')
            ->count(),

        'registrations' => Event::query()
            ->withCount('registrations')
            ->get()
            ->sum('registrations_count'),
    ];

    /*
    |--------------------------------------------------------------------------
    | Response
    |--------------------------------------------------------------------------
    */

    return Inertia::render('Admin/Event/Index', [
        'events' => $events,

        'categories' => $categories,

        'stats' => $stats,

        'qfilters' => [
            'search' => $request->input('search', ''),
            'category' => $request->input('category', ''),
            'status' => $request->input('status', ''),
        ],
    ]);
}

    /**
     * Show the event creation form.
     */
    public function create(): Response
    {
        $categories = EventCategory::query()
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'slug',
            ]);

        return Inertia::render('Admin/Event/Create', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store a new event.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => [
                'required',
                'string',
                'max:255',
                'unique:events,slug',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'event_type' => [
                'required',
                'in:in_person,online,hybrid',
            ],

            'status' => [
                'required',
                'in:draft,published,cancelled',
            ],

            'visibility' => [
                'required',
                'in:public,private',
            ],

            'start_date' => [
                'required',
                'date',
            ],

            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],

            'location_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'venue' => [
                'nullable',
                'string',
                'max:255',
            ],

            'city' => [
                'nullable',
                'string',
                'max:255',
            ],

            'country' => [
                'nullable',
                'string',
                'max:255',
            ],

            'online_url' => [
                'nullable',
                'url',
                'max:2048',
            ],

            'max_attendees' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'cover_image' => [
                'nullable',
                'string',
                'max:2048',
            ],

            'banner' => [
                'nullable',
                'string',
                'max:2048',
            ],

            'category_ids' => [
                'nullable',
                'array',
            ],

            'category_ids.*' => [
                'integer',
                'exists:event_categories,id',
            ],
        ]);

        $categoryIds = $validated['category_ids'] ?? [];

        unset($validated['category_ids']);

        $event = Event::create($validated);

        $event->categories()->sync($categoryIds);

        return redirect()
            ->route('admin.events.show', $event)
            ->with('success', 'Event created successfully.');
    }

    /**
     * Display the event management page.
     */
    public function show(Event $event): Response
    {
        $event->load([
            'categories',

            'sessions' => fn ($query) => $query
                ->orderBy('position')
                ->orderBy('start_time'),

            'sessions.speakers',

            'tickets' => fn ($query) => $query
                ->orderBy('position'),

            'media' => fn ($query) => $query
                ->orderBy('position'),
        ]);

        $event->loadCount('registrations');

        return Inertia::render('Admin/Event/Show', [
            'event' => $event,
        ]);
    }

    /**
     * Show the event edit form.
     */
    public function edit(Event $event): Response
    {
        $event->load('categories');

        $categories = EventCategory::query()
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'slug',
            ]);

        return Inertia::render('Admin/Event/Edit', [
            'event' => $event,
            'categories' => $categories,
        ]);
    }

    /**
     * Update an event.
     */
    public function update(
        Request $request,
        Event $event
    ): RedirectResponse {
        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => [
                'required',
                'string',
                'max:255',
                'unique:events,slug,' . $event->id,
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'event_type' => [
                'required',
                'in:in_person,online,hybrid',
            ],

            'status' => [
                'required',
                'in:draft,published,cancelled',
            ],

            'visibility' => [
                'required',
                'in:public,private',
            ],

            'start_date' => [
                'required',
                'date',
            ],

            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],

            'location_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'venue' => [
                'nullable',
                'string',
                'max:255',
            ],

            'city' => [
                'nullable',
                'string',
                'max:255',
            ],

            'country' => [
                'nullable',
                'string',
                'max:255',
            ],

            'online_url' => [
                'nullable',
                'url',
                'max:2048',
            ],

            'max_attendees' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'cover_image' => [
                'nullable',
                'string',
                'max:2048',
            ],

            'banner' => [
                'nullable',
                'string',
                'max:2048',
            ],

            'category_ids' => [
                'nullable',
                'array',
            ],

            'category_ids.*' => [
                'integer',
                'exists:event_categories,id',
            ],
        ]);

        $categoryIds = $validated['category_ids'] ?? [];

        unset($validated['category_ids']);

        $event->update($validated);

        $event->categories()->sync($categoryIds);

        return redirect()
            ->route('admin.events.show', $event)
            ->with('success', 'Event updated successfully.');
    }

    /**
     * Delete an event.
     */
    public function destroy(Event $event): RedirectResponse
    {
        $event->delete();

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event deleted successfully.');
    }

    /**
     * Publish an event.
     */
    public function publish(Event $event): RedirectResponse
    {
        $event->update([
            'status' => 'published',
        ]);

        return back()->with(
            'success',
            'Event published successfully.'
        );
    }

    /**
     * Unpublish an event.
     */
    public function unpublish(Event $event): RedirectResponse
    {
        $event->update([
            'status' => 'draft',
        ]);

        return back()->with(
            'success',
            'Event unpublished successfully.'
        );
    }
}