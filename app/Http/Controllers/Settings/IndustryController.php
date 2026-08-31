<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Industry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class IndustryController extends Controller
{
    public function edit(Request $request): Response
    {
        $user = $request->user();

        return Inertia::render('settings/Industries', [
            'industries' => Industry::query()
                ->orderBy('name')
                ->get([
                    'id',
                    'name',
                ]),

            'industryIds' => $user->industries()
                ->pluck('industries.id')
                ->values()
                ->all(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'industry_ids' => [
                'nullable',
                'array',
            ],

            'industry_ids.*' => [
                'integer',
                'exists:industries,id',
            ],
        ]);

        $request->user()->industries()->sync(
            $validated['industry_ids'] ?? []
        );

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('Industries updated.'),
        ]);

        return to_route('settings.industries.edit');
    }
}
