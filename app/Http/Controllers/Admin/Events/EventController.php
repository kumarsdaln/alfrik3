<?php

namespace App\Http\Controllers\Admin\Events;

use App\Http\Controllers\Controller;
use App\Models\Event\Event;
use App\Models\Event\EventCategory;
use App\Support\Breadcrumbs\BreadcrumbBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $activeCategory = $request->query('category');
        // upcoming | past — defaults to upcoming.
        $when = $request->query('when') === 'past' ? 'past' : 'upcoming';

        $query = Event::query()
            ->published()
            ->public()
            ->with(['categories:id,name,slug'])
            ->withCount(['registrations', 'sessions'])
            ->when($activeCategory, fn ($q) => $q->whereHas(
                'categories',
                fn ($c) => $c->where('slug', $activeCategory)
            ))
            ->when($search !== '', fn ($q) => $q->where(function ($w) use ($search) {
                $w->where('title', 'ilike', "%{$search}%")
                    ->orWhere('description', 'ilike', "%{$search}%")
                    ->orWhere('city', 'ilike', "%{$search}%");
            }));

        if ($when === 'past') {
            $query->past()->orderByDesc('start_date');
        } else {
            $query->upcoming()->orderBy('start_date');
        }

        // Featured = the next upcoming event, only on the unfiltered landing.
        $featured = null;
        if ($when === 'upcoming' && ! $activeCategory && $search === '') {
            $featured = Event::query()
                ->published()->public()->upcoming()
                ->with(['categories:id,name,slug'])
                ->withCount('registrations')
                ->orderBy('start_date')
                ->first();
        }

        return Inertia::render('Events/Index', [
            'events' => Inertia::scroll(fn () => $query->paginate(12)->withQueryString()),
            'featured' => $featured,
            'categories' => EventCategory::query()
                ->withCount(['events'])
                ->orderBy('name')
                ->get(['id', 'name', 'slug']),
            'counts' => [
                'upcoming' => Event::published()->public()->upcoming()->count(),
                'past' => Event::published()->public()->past()->count(),
            ],
            'qfilters' => [
                'search' => $search,
                'category' => $activeCategory,
                'when' => $when,
            ],
            'breadcrumbs' => BreadcrumbBuilder::make()->home()->add('Events')->toArray(),
        ]);
    }

    public function show(Event $event)
    {
        abort_unless(
            $event->status === 'published' && $event->visibility === 'public',
            404,
        );

        $event->load([
            'categories:id,name,slug',
            'creator:id,name,external_id,profile_image',
            'sessions.speakers:id,name,external_id,profile_image',
            'reviews.user:id,name,external_id,profile_image',
        ])->loadCount(['registrations', 'sessions', 'participants']);

        $userId = Auth::id();

        $registration = [
            'registrations_count' => $event->registrations_count,
            'is_registered' => $userId
                ? $event->registrations()->where('user_id', $userId)->exists()
                : false,
            'spots_left' => $event->max_attendees
                ? max(0, $event->max_attendees - $event->registrations_count)
                : null,
        ];

        $reviewStats = [
            'average' => round((float) $event->reviews()->avg('rating'), 1),
            'count' => $event->reviews_count ?? $event->reviews->count(),
        ];

        // Speakers across the whole programme (unique).
        $speakers = $event->sessions
            ->flatMap->speakers
            ->unique('id')
            ->values();

        $related = Event::query()
            ->published()->public()->upcoming()
            ->with('categories:id,name,slug')
            ->withCount('registrations')
            ->where('id', '!=', $event->id)
            ->orderBy('start_date')
            ->take(3)
            ->get();

        return Inertia::render('Events/Show', [
            'event' => $event,
            'speakers' => $speakers,
            'registration' => $registration,
            'reviewStats' => $reviewStats,
            'related' => $related,
            'breadcrumbs' => BreadcrumbBuilder::make()->home()
                ->add('Events', route('events.index'))
                ->add($event->title)
                ->toArray(),
        ]);
    }

    /**
     * Toggle the current user's registration for an event.
     */
    public function register(Event $event)
    {
        abort_unless(
            $event->status === 'published' && $event->visibility === 'public',
            404,
        );

        $existing = $event->registrations()->where('user_id', Auth::id())->first();

        if ($existing) {
            $existing->delete();
            $registered = false;
        } else {
            $event->registrations()->create([
                'user_id' => Auth::id(),
                'status' => 'confirmed',
                'qr_code' => strtoupper(Str::random(10)),
            ]);
            $registered = true;
        }

        return response()->json([
            'registered' => $registered,
            'registrations_count' => $event->registrations()->count(),
        ]);
    }
}
