<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\UsernameUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UsernameController extends Controller
{
    /**
     * Show username settings.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('settings/Username', [
            'username' => $request->user()->username,
        ]);
    }

    /**
     * Update the user's username.
     */
    public function update(
        UsernameUpdateRequest $request
    ): RedirectResponse {
        $request->user()->update([
            'username' => $request->validated('username'),
        ]);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('Username updated.'),
        ]);

        return to_route('settings.username.edit');
    }
}