<?php

namespace App\Http\Controllers\Admin\Media;

use App\Actions\Media\DeleteMedia;
use App\Actions\Media\UpdateMedia;
use App\Http\Controllers\Controller;
use App\Http\Requests\Media\UpdateMediaRequest;
use App\Http\Resources\MediaResource;
use App\Models\Media;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class MediaController extends Controller
{
    public function edit(Media $media): Response
    {
        return Inertia::render(
            'admin/media/Edit',
            [
                'media' => MediaResource::make($media),
            ]
        );
    }

    public function update(
        UpdateMediaRequest $request,
        Media $media,
        UpdateMedia $action,
    ): RedirectResponse {
        $action->handle(
            $media,
            $request->validated(),
        );

        return back()->with(
            'success',
            'Media updated successfully.'
        );
    }

    public function destroy(
        Media $media,
        DeleteMedia $action,
    ): RedirectResponse {
        $action->handle($media);

        return back()->with(
            'success',
            'Media deleted successfully.'
        );
    }
}
