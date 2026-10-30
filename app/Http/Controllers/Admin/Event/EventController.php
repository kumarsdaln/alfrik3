<?php

namespace App\Http\Controllers\Admin\Event;

use App\Actions\Event\CreateEvent;
use App\Actions\Event\DeleteEvent;
use App\Actions\Event\UpdateEvent;
use App\Enums\Event\EventStatus;
use App\Enums\Event\EventType;
use App\Enums\Event\EventVisibility;
use App\Http\Controllers\Controller;
use App\Http\Requests\Event\StoreEventRequest;
use App\Http\Requests\Event\UpdateEventRequest;
use App\Http\Resources\Event\EventResource;
use App\Models\Event\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EventController extends Controller
{
    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Event::class);

        $events = Event::query()
            ->with('creator:id,name,username,avatar')
            ->when(
                $request->string('search')->toString(),
                fn($query, $search) => $query->where(
                    'title',
                    'like',
                    "%{$search}%",
                ),
            )
            ->when(
                $request->input('status'),
                fn($query, $status) => $query->where('status', $status),
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total' => Event::count(),
            'published' => Event::where('status', 'published')->count(),
            'draft' => Event::where('status', 'draft')->count(),
            'upcoming' => Event::where('status', 'published')
                ->where('start_date', '>=', now())
                ->count(),
        ];

        return Inertia::render('admin/event/Index', [
            'events' => EventResource::collection($events),
            'filters' => $request->only(['search', 'status']),
            'statusOptions' => EventStatus::dropdown(),
            'stats' => $stats,
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Event::class);

        return Inertia::render('admin/event/Create', [
            'typeOptions' => EventType::dropdown(),
            'visibilityOptions' => EventVisibility::dropdown(),
            'statusOptions' => EventStatus::dropdown(),
        ]);
    }

    public function store(
        StoreEventRequest $request,
        CreateEvent $action,
    ): RedirectResponse {
        $this->authorize('create', Event::class);

        $event = $action->handle([
            ...$request->validated(),
            'created_by' => $request->user()->id,
        ]);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Event created successfully.')]);

        return to_route('admin.events.show', $event)
            ->with('success', 'Event created successfully.');
    }

    public function show(Event $event): Response
    {
        $this->authorize('view', $event);

        $event->load([
            'creator:id,name',
            'categories',
            'tags',
            'sessions',
            'participants.user:id,name',
            'tickets',
        ]);

        return Inertia::render('admin/event/Show', [
            'event' => new EventResource($event),
        ]);
    }

    public function edit(Event $event): Response
    {
        $this->authorize('update', $event);

        return Inertia::render('admin/event/Edit', [
            'event' => $event,
            'typeOptions' => EventType::dropdown(),
            'visibilityOptions' => EventVisibility::dropdown(),
            'statusOptions' => EventStatus::dropdown(),
        ]);
    }

    public function update(
        UpdateEventRequest $request,
        Event $event,
        UpdateEvent $action,
    ): RedirectResponse {
        $this->authorize('update', $event);

        $action->handle($event, $request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Event updated successfully.')]);

        return to_route('admin.events.show', $event)
            ->with('success', 'Event updated successfully.');
    }

    public function destroy(
        Request $request,
        Event $event,
        DeleteEvent $action,
    ): RedirectResponse {
        $this->authorize('delete', $event);

        $action->handle($event);
        Inertia::flash('toast', ['type' => 'success', 'message' => __('Event deleted successfully.')]);
        return to_route('admin.events.index')
            ->with('success', 'Event deleted successfully.');
    }
}
