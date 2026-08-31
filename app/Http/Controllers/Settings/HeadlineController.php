<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\HeadlineUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HeadlineController extends Controller
{
    /**
     * Show headline settings.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('settings/Headline', [
            'headline' => $request->user()->headline,
        ]);
    }

    /**
     * Update the user's headline.
     */
    public function update(
        HeadlineUpdateRequest $request
    ): RedirectResponse {
        $request->user()->update([
            'headline' => $request->validated('headline'),
        ]);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('Headline updated.'),
        ]);

        return to_route('settings.headline.edit');
    }
}