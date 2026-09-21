<?php

namespace App\Http\Controllers\Admin\Category;

use App\Actions\Category\SyncCategories;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Support\ModelResolver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CategoryAssignmentController extends Controller
{
    public function index(
        Request $request,
        string $type,
        int $id,
    ): Response {
        $model = ModelResolver::resolve($type, $id);

        abort_unless(
            method_exists($model, 'categories'),
            404
        );

        $model->load('categories');

        return Inertia::render('admin/category/Assign', [
            'model' => [
                'type' => $type,
                'id' => $model->id,
                'title' => $this->modelTitle($model),
            ],

            'categories' => [
                'assigned' => CategoryResource::collection(
                    $model->categories
                ),

                'options' => Inertia::scroll(
                    fn() => CategoryResource::collection(
                        Category::query()
                            ->select([
                                'id',
                                'name',
                                'slug',
                                'sort_order',
                            ])
                            ->where('status', true)
                            ->when(
                                $request->filled('search'),
                                fn($query) => $query->where(function ($query) use ($request) {
                                    $search = $request->input('search');

                                    $query
                                        ->where('name', 'ilike', "%{$search}%")
                                        ->orWhere('slug', 'ilike', "%{$search}%");
                                })
                            )
                            ->orderBy('sort_order')
                            ->orderBy('name')
                            ->paginate(20)
                            ->withQueryString()
                    )
                ),
            ],
        ]);
    }

    public function update(
        Request $request,
        string $type,
        int $id,
        SyncCategories $action,
    ): RedirectResponse {
        $model = ModelResolver::resolve($type, $id);

        abort_unless(
            method_exists($model, 'categories'),
            404
        );

        $validated = $request->validate([
            'category_ids' => [
                'nullable',
                'array',
            ],

            'category_ids.*' => [
                'integer',
                'exists:categories,id',
            ],
        ]);

        $action->handle(
            $model,
            $validated['category_ids'] ?? []
        );

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('Categories updated successfully.'),
        ]);

        return back();
    }

    private function modelTitle(Model $model): string
    {
        return $model->title
            ?? $model->name
            ?? class_basename($model);
    }
}
