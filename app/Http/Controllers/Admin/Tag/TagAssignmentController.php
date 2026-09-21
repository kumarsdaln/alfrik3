<?php

namespace App\Http\Controllers\Admin\Tag;

use App\Actions\Tag\SyncTags;
use App\Http\Controllers\Controller;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use App\Support\ModelResolver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TagAssignmentController extends Controller
{
    public function index(
        Request $request,
        string $type,
        int $id,
    ): Response {
        $model = ModelResolver::resolve($type, $id);

        abort_unless(
            method_exists($model, 'tags'),
            404
        );

        $model->load('tags');

        return Inertia::render('admin/tag/Assign', [
            'model' => [
                'type' => $type,
                'id' => $model->id,
                'title' => $this->modelTitle($model),
            ],

            'tags' => [
                'assigned' => TagResource::collection(
                    $model->tags
                ),

                'options' => Inertia::scroll(
                    fn() => TagResource::collection(
                        Tag::query()
                            ->select([
                                'id',
                                'name',
                                'slug',
                            ])
                            ->where('status', true)
                            ->when(
                                $request->filled('search'),
                                function ($query) use ($request) {
                                    $search = $request->input('search');

                                    $query->where(function ($query) use ($search) {
                                        $query
                                            ->where('name', 'ilike', "%{$search}%")
                                            ->orWhere('slug', 'ilike', "%{$search}%");
                                    });
                                }
                            )
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
        SyncTags $action,
    ): RedirectResponse {
        $model = ModelResolver::resolve($type, $id);

        abort_unless(
            method_exists($model, 'tags'),
            404
        );

        $validated = $request->validate([
            'tag_ids' => [
                'nullable',
                'array',
            ],

            'tag_ids.*' => [
                'integer',
                'exists:tags,id',
            ],
        ]);

        $action->handle(
            $model,
            $validated['tag_ids'] ?? []
        );

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('Tags updated successfully.'),
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
