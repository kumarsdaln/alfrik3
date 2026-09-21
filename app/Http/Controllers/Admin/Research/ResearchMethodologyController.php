<?php

namespace App\Http\Controllers\Admin\Research;

use App\Actions\Research\SaveResearchMethodology;
use App\Http\Controllers\Controller;
use App\Http\Requests\Research\SaveResearchMethodologyRequest;
use App\Models\Research\Research;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ResearchMethodologyController extends Controller
{
    public function edit(Research $research): Response
    {
        $research->load('methodology');

        return Inertia::render('admin/research/Methodology', [
            'research' => $research,
            'methodology' => $research->methodology,
        ]);
    }

    public function update(
        SaveResearchMethodologyRequest $request,
        Research $research,
        SaveResearchMethodology $action,
    ): RedirectResponse {
        $action->handle(
            research: $research,
            data: $request->validated(),
        );

        return back();
    }
}
