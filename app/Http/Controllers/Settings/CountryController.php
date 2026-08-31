<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class CountryController extends Controller
{
    public function edit(Request $request): Response
    {
        return Inertia::render('settings/Location', [
            'countries' => Country::query()
                ->orderBy('name')
                ->get([
                    'id',
                    'name',
                ]),

            'countryId' => $request->user()->country_id,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'country_id' => [
                'nullable',
                'integer',
                'exists:countries,id',
            ],
        ]);

        $request->user()->update([
            'country_id' => $validated['country_id'] ?? null,
        ]);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('Country updated.'),
        ]);

        return to_route('settings.country.edit');
    }
}
