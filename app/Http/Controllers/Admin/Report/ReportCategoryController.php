<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\Report\ReportCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportCategoryController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Reports/Category/Index', [
            'categories' => ReportCategory::withCount('reports')->orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Reports/Category/Create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateCategory($request);
        $validated['slug'] = ReportCategory::uniqueSlug($validated['name']);

        ReportCategory::create($validated);

        return redirect()->route('admin.reports.categories.index')->with('success', 'Category created.');
    }

    public function edit(ReportCategory $category)
    {
        return Inertia::render('Admin/Reports/Category/Edit', ['category' => $category]);
    }

    public function update(Request $request, ReportCategory $category)
    {
        $validated = $this->validateCategory($request);
        $validated['slug'] = ReportCategory::uniqueSlug($validated['name'], $category->id);

        $category->update($validated);

        return redirect()->route('admin.reports.categories.index')->with('success', 'Category updated.');
    }

    public function destroy(ReportCategory $category)
    {
        $category->reports()->update(['category_id' => null]);
        $category->delete();

        return redirect()->route('admin.reports.categories.index')->with('success', 'Category deleted.');
    }

    private function validateCategory(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
        ]);
    }
}
