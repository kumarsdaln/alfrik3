<?php

namespace App\Http\Controllers\Admin\Category;

use App\Actions\Category\CreateCategory;
use App\Actions\Category\DeleteCategory;
use App\Actions\Category\UpdateCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Support\Breadcrumbs\Breadcrumb;
use App\Support\Breadcrumbs\BreadcrumbBuilder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    public function index(Request $request): Response
    {
        $categories = Category::query()
            ->with('parent')
            ->when(
                $request->filled('search'),
                function ($query) use ($request) {
                    $search = $request
                        ->string('search')
                        ->trim()
                        ->toString();

                    $query->where(function ($query) use ($search) {
                        $query
                            ->where('name', 'ilike', "%{$search}%")
                            ->orWhere('slug', 'ilike', "%{$search}%");
                    });
                }
            )
            ->when(
                $request->filled('status'),
                fn($query) => $query->where(
                    'status',
                    $request->string('status')->toString() === '1'
                )
            )
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('admin/category/Index', [
            'categories' => CategoryResource::collection($categories),

            'filters' => [
                'search' => $request->string('search')->toString(),
                'status' => $request->string('status')->toString(),
            ],

            'stats' => [
                'total' => Category::count(),
                'active' => Category::where('status', true)->count(),
                'inactive' => Category::where('status', false)->count(),
            ],

            'breadcrumbs' => BreadcrumbBuilder::make()
                             ->admin()
                             ->add('Categories')
                             ->toArray()
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/category/Create', [
            'parentCategories' => CategoryResource::collection(
                Category::query()
                    ->where('status', true)
                    ->orderBy('name')
                    ->get()
            ),
            'breadcrumbs' => BreadcrumbBuilder::make()
                             ->admin()
                             ->add('Categories', route('admin.categories.index'))
                             ->add('Create', route('admin.categories.create'))
                             ->toArray()
        ]);
    }

    public function store(
        StoreCategoryRequest $request,
        CreateCategory $action,
    ): RedirectResponse {
        $category = $action->handle(
            $request->validated()
        );
        Inertia::flash('toast', ['type' => 'success', 'message' => __('Category created successfully.')]);
        return to_route(
            'admin.categories.edit',
            $category
        );
    }

    public function edit(Category $category): Response
    {
        return Inertia::render('admin/category/Edit', [
            'category' => new CategoryResource($category),

            'parentCategories' => CategoryResource::collection(
                Category::query()
                    ->where('status', true)
                    ->whereKeyNot($category->id)
                    ->orderBy('name')
                    ->get()
            ),

            'breadcrumbs' => BreadcrumbBuilder::make()
                             ->admin()
                             ->add('Categories', route('admin.categories.index'))
                             ->add("{$category->name} - Edit", route('admin.categories.edit', $category->id))
                             ->toArray()
        ]);
    }

    public function update(
        UpdateCategoryRequest $request,
        Category $category,
        UpdateCategory $action,
    ): RedirectResponse {
        $action->handle(
            $category,
            $request->validated()
        );

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Category updated successfully.')]);
        return back();
    }

    public function destroy(
        Category $category,
        DeleteCategory $action,
    ): RedirectResponse {
        $action->handle($category);

        return to_route(
            'admin.categories.index'
        )->with(
            'success',
            'Category deleted successfully.'
        );
    }
}
