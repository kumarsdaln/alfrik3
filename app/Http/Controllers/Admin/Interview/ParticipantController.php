<?php

namespace App\Http\Controllers\Admin\Interview;

use App\Http\Controllers\Controller;
use App\Models\Interview\Interview;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    /**
     * Show create form
     */
    public function index(Interview $interview)
    {
        $interview->load('participants.user');
        return inertia('Admin/Interviews/Participants', [
            'interview' => $interview
        ]);
    }

    /**
     * Store participants (MULTIPLE with roles)
     */
    public function save(Request $request, Interview $interview)
    {
        $validated = $request->validate([
            'participants' => ['required', 'array', 'min:1'],
            'participants.*.user_id' => ['required', 'exists:users,id'],
            'participants.*.role' => ['required', 'in:interviewer,interviewee'],
        ]);

        $incoming = collect($validated['participants']);

        // -------------------------
        // 1. DELETE removed users
        // -------------------------
        $incomingIds = $incoming->pluck('user_id')->toArray();

        $interview->participants()
            ->whereNotIn('user_id', $incomingIds)
            ->delete();

        // -------------------------
        // 2. UPSERT (update or insert)
        // -------------------------
        foreach ($incoming as $p) {
            $interview->participants()->updateOrCreate(
                [
                    'user_id' => $p['user_id'], // match
                ],
                [
                    'role' => $p['role'], // update
                ]
            );
        }

        return redirect()
            ->route('admin.interviews.questions.index', $interview->id)
            ->with('success', 'Participants updated successfully.');
    }
}
