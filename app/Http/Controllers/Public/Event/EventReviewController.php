<?php

namespace App\Http\Controllers\Public\Event;

use App\Http\Controllers\Controller;
use App\Models\Event\Event;
use App\Models\Event\EventReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EventReviewController extends Controller
{
    public function store(
        Request $request,
        Event $event
    ): RedirectResponse {
        $validated = $request->validate([
            'rating' => [
                'required',
                'integer',
                'min:1',
                'max:5',
            ],

            'title' => [
                'nullable',
                'string',
                'max:255',
            ],

            'review' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        $user = $request->user();

        abort_unless($user, 401);

        $registered = $event->registrations()
            ->where('user_id', $user->id)
            ->whereIn('status', [
                'confirmed',
            ])
            ->exists();

        if (! $registered) {
            return back()->withErrors([
                'review' =>
                'You must attend this event before reviewing it.',
            ]);
        }

        $alreadyReviewed = $event->reviews()
            ->where('user_id', $user->id)
            ->exists();

        if ($alreadyReviewed) {
            return back()->withErrors([
                'review' =>
                'You have already reviewed this event.',
            ]);
        }

        EventReview::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'rating' => $validated['rating'],
            'title' => $validated['title'] ?? null,
            'review' => $validated['review'] ?? null,
            'status' => 'pending',
        ]);

        return back()->with(
            'success',
            'Your review has been submitted for moderation.'
        );
    }

    public function update(
        Request $request,
        EventReview $review
    ): RedirectResponse {
        abort_unless(
            $review->user_id === $request->user()->id,
            403
        );

        $validated = $request->validate([
            'rating' => [
                'required',
                'integer',
                'min:1',
                'max:5',
            ],

            'title' => [
                'nullable',
                'string',
                'max:255',
            ],

            'review' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        $review->update([
            'rating' => $validated['rating'],
            'title' => $validated['title'] ?? null,
            'review' => $validated['review'] ?? null,
            'status' => 'pending',
        ]);

        return back()->with(
            'success',
            'Your review has been updated and sent for moderation.'
        );
    }

    public function destroy(
        EventReview $review
    ): RedirectResponse {
        abort_unless(
            $review->user_id === auth()->id(),
            403
        );

        $review->delete();

        return back()->with(
            'success',
            'Review deleted successfully.'
        );
    }
}
