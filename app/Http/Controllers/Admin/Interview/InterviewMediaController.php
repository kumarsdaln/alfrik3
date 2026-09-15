<?php

namespace App\Http\Controllers\Admin\Interview;

use App\Actions\Media\CreateMedia;
use App\Http\Controllers\Controller;
use App\Http\Requests\Media\StoreMediaRequest;
use App\Http\Resources\MediaResource;
use App\Models\Interview\Interview;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class InterviewMediaController extends Controller
{
    public function index(Interview $interview): Response
    {
        $interview->load('media');

        return Inertia::render('admin/interview/media/Index', [
            'interview' => [
                'id' => $interview->id,
                'title' => $interview->title,
            ],

            'media' => MediaResource::collection(
                $interview->media
            ),
        ]);
    }

    public function store(
        StoreMediaRequest $request,
        Interview $interview,
        CreateMedia $action
    ): RedirectResponse {
        $action->handle(
            model: $interview,
            file: $request->file('file'),
            collection: $request->input('collection'),
            name: $request->input('name'),
            alt: $request->input('alt'),
        );

        return back()->with(
            'success',
            'Media uploaded successfully.'
        );
    }
}