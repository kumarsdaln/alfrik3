<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Media\CreateMedia;
use App\Http\Controllers\Controller;
use App\Http\Requests\Media\StoreMediaRequest;
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
            'interview' => $interview,
            'media' => $interview->media,
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
            collection: $request->string('collection')->toString(),
            name: $request->input('name'),
            alt: $request->input('alt'),
        );

        return back()->with(
            'success',
            'Media uploaded successfully.'
        );
    }
}