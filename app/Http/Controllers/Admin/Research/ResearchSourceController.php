<?php

namespace App\Http\Controllers\Admin\Research;

use App\Actions\Research\CreateResearchSource;
use App\Actions\Research\DeleteResearchSource;
use App\Actions\Research\UpdateResearchSource;
use App\Enums\Research\ResearchSourceType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Research\StoreResearchSourceRequest;
use App\Http\Requests\Research\UpdateResearchSourceRequest;
use App\Models\Research\Research;
use App\Models\Research\ResearchSource;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ResearchSourceController extends Controller
{
    public function index(Research $research): Response
    {
        $research->load([
            'sources',
        ]);

        return Inertia::render('admin/research/Sources', [
            'research' => [
                'id' => $research->id,
                'title' => $research->title,
            ],
            'sources' => $research->sources,
            'sourceTypeOptions' => ResearchSourceType::dropdown(),
        ]);
    }

    public function store(
        StoreResearchSourceRequest $request,
        Research $research,
        CreateResearchSource $action,
    ): RedirectResponse {
        $action->handle(
            research: $research,
            data: $request->validated(),
        );

        return back();
    }

    public function edit(
        Research $research,
        ResearchSource $source,
    ): Response {
        abort_unless(
            $source->research_id === $research->id,
            404
        );

        return Inertia::render('admin/research/source/Edit', [
            'research' => [
                'id' => $research->id,
                'title' => $research->title,
            ],
            'source' => $source,
            'sourceTypeOptions' => ResearchSourceType::dropdown(),
        ]);
    }

    public function update(
        UpdateResearchSourceRequest $request,
        Research $research,
        ResearchSource $source,
        UpdateResearchSource $action,
    ): RedirectResponse {
        abort_unless(
            $source->research_id === $research->id,
            404
        );

        $action->handle(
            source: $source,
            data: $request->validated(),
        );

        return back();
    }

    public function destroy(
        Research $research,
        ResearchSource $source,
        DeleteResearchSource $action,
    ): RedirectResponse {
        abort_unless(
            $source->research_id === $research->id,
            404
        );

        $action->handle($source);

        return back();
    }
}
