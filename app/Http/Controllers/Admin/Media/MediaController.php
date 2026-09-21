<?php

namespace App\Http\Controllers\Admin\Media;

use App\Actions\Media\CreateMedia;
use App\Actions\Media\DeleteMedia;
use App\Actions\Media\UpdateMedia;
use App\Http\Controllers\Controller;
use App\Http\Requests\Media\StoreMediaRequest;
use App\Http\Requests\Media\UpdateMediaRequest;
use App\Http\Resources\MediaResource;
use App\Support\ModelResolver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class MediaController extends Controller
{
    public function index(
        string $type,
        int $id,
    ): Response {
        $model = ModelResolver::resolve($type, $id);

        abort_unless(
            method_exists($model, 'media'),
            404
        );

        $media = $model->media()
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('admin/media/Index', [
            'model' => [
                'type' => $type,
                'id' => $model->id,
                'title' => $this->modelTitle($model),
            ],

            'media' => MediaResource::collection($media),
        ]);
    }

    public function store(
        StoreMediaRequest $request,
        string $type,
        int $id,
        CreateMedia $action,
    ): RedirectResponse {
        $model = ModelResolver::resolve($type, $id);

        abort_unless(
            method_exists($model, 'media'),
            404
        );

        $data = $request->validated();

        $action->handle(
            model: $model,
            file: $request->file('file'),
            collection: $data['collection'] ?? 'default',
            name: $data['name'] ?? null,
            alt: $data['alt'] ?? null,
        );

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('Media created successfully.'),
        ]);

        return back();
    }

    public function edit(
        string $type,
        int $id,
        int $mediaId,
    ): Response {
        $model = ModelResolver::resolve($type, $id);

        abort_unless(
            method_exists($model, 'media'),
            404
        );

        $media = $model->media()
            ->whereKey($mediaId)
            ->firstOrFail();

        return Inertia::render('admin/media/Edit', [
            'model' => [
                'type' => $type,
                'id' => $model->id,
                'title' => $this->modelTitle($model),
            ],
            'media' => new MediaResource($media),
        ]);
    }

    public function update(
        UpdateMediaRequest $request,
        string $type,
        int $id,
        int $mediaId,
        UpdateMedia $action,
    ): RedirectResponse {
        $model = ModelResolver::resolve($type, $id);

        abort_unless(
            method_exists($model, 'media'),
            404
        );

        $media = $model->media()
            ->whereKey($mediaId)
            ->firstOrFail();

        $action->handle(
            media: $media,
            data: $request->validated(),
        );

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('Media updated successfully.'),
        ]);

        return back();
    }

    public function destroy(
        string $type,
        int $id,
        int $mediaId,
        DeleteMedia $action,
    ): RedirectResponse {
        $model = ModelResolver::resolve($type, $id);

        abort_unless(
            method_exists($model, 'media'),
            404
        );

        $media = $model->media()
            ->whereKey($mediaId)
            ->firstOrFail();

        $action->handle($media);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('Media deleted successfully.'),
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
