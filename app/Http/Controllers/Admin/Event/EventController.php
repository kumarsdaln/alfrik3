<?php

namespace App\Http\Controllers\Admin\Event;

use App\Enums\Event\EventStatus;
use App\Enums\Event\EventType;
use App\Enums\Event\EventVisibility;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Event\StoreEventRequest;
use App\Http\Requests\Admin\Event\UpdateEventRequest;
use App\Models\Event\Event;
use App\Models\Event\EventCategory;
use App\Support\Breadcrumbs\BreadcrumbBuilder;
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
        ->paginate(10)
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

    return Inertia::render('admin/event/Index', [
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

        return Inertia::render('admin/event/Create', [
            'breadcrumbs' => BreadcrumbBuilder::make()
                ->add('Events', route('admin.events.index'))
                ->add('Create Event')
                ->toArray(),
            'categories' => $categories,
            'statusOptions' => EventStatus::dropdown(),
            'typeOptions' => EventType::dropdown(),
            'visibilityOptions' => EventVisibility::dropdown(),
        ]);
    }

    /**
     * Store a new event.
     */
    public function store(StoreEventRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $categoryIds = $data['category_ids'] ?? [];

        unset($data['category_ids']);

        $event = Event::create($data);

        $event->categories()->sync($categoryIds);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Event Created.')]);
        return to_route('admin.events.show', $event);
    }

    /**
     * Display the event management page.
     */
    public function show(Event $event): Response
    {
        $event->load([
            'categories',
            'participants.user',
            'sessions.speakers',
            'tickets',
            'media',
        ]);

        $registrationStats = [
            'total' => $event->registrations()->count(),

            'confirmed' => $event->registrations()
                ->where('status', 'confirmed')
                ->count(),

            'pending' => $event->registrations()
                ->where('status', 'pending')
                ->count(),

            'cancelled' => $event->registrations()
                ->where('status', 'cancelled')
                ->count(),

            'checked_in' => $event->registrations()
                ->whereHas('checkins', function ($query) {
                    $query->where('successful', true);
                })
                ->count(),
        ];

        return Inertia::render('admin/event/Show', [
            'breadcrumbs' => BreadcrumbBuilder::make()
                ->home()
                ->add('Events', route('admin.events.index'))
                ->add($event->title)
                ->toArray(),
            'event' => array_merge(
                $event->toArray(),
                [
                    'registration_stats' => $registrationStats,
                ],
            ),
        ]);
    }

    /**
     * Show the event edit form.
     */
    public function edit(Event $event): Response
    {
        $event->load('categories');

        return Inertia::render('admin/event/Edit', [
            'breadcrumbs' => BreadcrumbBuilder::make()
                ->home()
                ->add('Events', route('admin.events.index'))
                ->add($event->title, route('admin.events.show', $event->id))
                ->add('Edit')
                ->toArray(),
            'event' => $event,
            'categories' => EventCategory::query()
                ->orderBy('name')
                ->get(['id', 'name', 'slug']),
            'statusOptions' => EventStatus::dropdown(),
            'typeOptions' => EventType::dropdown(),
            'visibilityOptions' => EventVisibility::dropdown(),
        ]);
    }

    /**
     * Update an event.
     */
    public function update(
        UpdateEventRequest $request,
        Event $event
    ): RedirectResponse {
        $data = $request->validated();

        $categoryIds = $data['category_ids'] ?? [];

        unset($data['category_ids']);

        if ($request->hasFile('banner')) {
            $data['banner'] = $request
                ->file('banner')
                ->store('events', 'public');
        }

        $event->update($data);

        $event->categories()->sync($categoryIds);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Event Updated.')]);
        return to_route('admin.events.show', $event);
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