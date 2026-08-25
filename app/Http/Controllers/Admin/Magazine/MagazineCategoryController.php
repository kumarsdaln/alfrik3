<?php

namespace App\Http\Controllers\Admin\Magazine;

use App\Http\Controllers\Controller;
use App\Models\Magazine\MagazineCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MagazineCategoryController extends Controller
{
    public function index()
    {
        $categories = MagazineCategory::query()
            ->withCount('magazines')
            ->orderBy('name')
            ->get();

        return Inertia::render('Admin/Magazine/Category/Index', [
            'categories' => $categories,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Magazine/Category/Create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateCategory($request);
        $validated['slug'] = MagazineCategory::uniqueSlug($validated['name']);

        MagazineCategory::create($validated);

        return redirect()
            ->route('admin.magazine.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit(MagazineCategory $category)
    {
        return Inertia::render('Admin/Magazine/Category/Edit', [
            'category' => $category,
        ]);
    }

    public function update(Request $request, MagazineCategory $category)
    {
        $validated = $this->validateCategory($request);
        $validated['slug'] = MagazineCategory::uniqueSlug($validated['name'], $category->id);

        $category->update($validated);

        return redirect()
            ->route('admin.magazine.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(MagazineCategory $category)
    {
        // Detach issues from the category before removing it.
        $category->magazines()->update(['category_id' => null]);
        $category->delete();

        return redirect()
            ->route('admin.magazine.categories.index')
            ->with('success', 'Category deleted.');
    }

    private function validateCategory(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:245'],
            'icon' => ['nullable', 'string', 'max:245'],
            'description' => ['nullable', 'string'],
            'meta_title' => ['nullable', 'string', 'max:100'],
            'meta_description' => ['nullable', 'string', 'max:300'],
            'meta_keywords' => ['nullable', 'string', 'max:300'],
        ]);
    }
}
