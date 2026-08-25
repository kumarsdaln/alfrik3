<?php

namespace App\Http\Controllers\Admin\Research;

use App\Http\Controllers\Controller;
use App\Models\Research\ResearchArea;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ResearchAreaController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Research/Area/Index', [
            'areas' => ResearchArea::withCount('papers')->orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Research/Area/Create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateArea($request);
        $validated['slug'] = ResearchArea::uniqueSlug($validated['name']);

        ResearchArea::create($validated);

        return redirect()->route('admin.research.areas.index')->with('success', 'Research area created.');
    }

    public function edit(ResearchArea $area)
    {
        return Inertia::render('Admin/Research/Area/Edit', ['area' => $area]);
    }

    public function update(Request $request, ResearchArea $area)
    {
        $validated = $this->validateArea($request);
        $validated['slug'] = ResearchArea::uniqueSlug($validated['name'], $area->id);

        $area->update($validated);

        return redirect()->route('admin.research.areas.index')->with('success', 'Research area updated.');
    }

    public function destroy(ResearchArea $area)
    {
        $area->papers()->update(['area_id' => null]);
        $area->delete();

        return redirect()->route('admin.research.areas.index')->with('success', 'Research area deleted.');
    }

    private function validateArea(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
        ]);
    }
}
