<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class LanguageController extends Controller
{
    public function edit(Request $request): Response
    {
        $user = $request->user();

        return Inertia::render('settings/Languages', [
            'languages' => Language::query()
                ->orderBy('name')
                ->get([
                    'id',
                    'name',
                    'native',
                ]),

            'languageIds' => $user->languages()
                ->pluck('languages.id')
                ->values()
                ->all(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'language_ids' => [
                'nullable',
                'array',
            ],

            'language_ids.*' => [
                'integer',
                'exists:languages,id',
            ],
        ]);

        $request->user()->languages()->sync(
            $validated['language_ids'] ?? []
        );

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('Languages updated.'),
        ]);

        return to_route('settings.languages.edit');
    }
}
