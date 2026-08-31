<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Position;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PositionController extends Controller
{
    /**
     * Show position settings.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('settings/Position', [
            'positions' => Position::query()
                ->orderBy('name')
                ->get([
                    'id',
                    'name',
                ]),

            'positionId' => $request->user()->position_id,
        ]);
    }

    /**
     * Update the user's position.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'position_id' => [
                'required',
                'integer',
                'exists:positions,id',
            ],
        ]);

        $request->user()->update([
            'position_id' => $validated['position_id'],
        ]);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('Position updated.'),
        ]);

        return to_route('settings.position.edit');
    }
}