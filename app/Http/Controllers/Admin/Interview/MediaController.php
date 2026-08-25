<?php

namespace App\Http\Controllers\Admin\Interview;

use App\Http\Controllers\Controller;
use App\Models\Interview\Interview;
use App\Models\Interview\InterviewMedia;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MediaController extends Controller
{
    public function __construct(protected FileUploadService $fileUploadService)
    {
    }

    /**
     * Create or update the primary media (video/audio) for an interview.
     * Supports an external embed URL (YouTube/Vimeo/etc.) or an uploaded file.
     */
    public function store(Request $request, Interview $interview)
    {
        $validated = $request->validate([
            'media_type' => ['required', 'in:video,audio'],
            'source_type' => ['required', 'in:upload,external'],
            'embed_url' => ['nullable', 'required_if:source_type,external', 'string', 'max:2048'],
            'file' => ['nullable', 'required_if:source_type,upload', 'file', 'mimes:mp4,webm,mov,ogg,mp3,wav,m4a,aac', 'max:102400'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'duration' => ['nullable', 'integer', 'min:0'],
        ]);

        $existing = $interview->media()->first();

        $data = [
            'media_type' => $validated['media_type'],
            'source_type' => $validated['source_type'],
            'duration' => $validated['duration'] ?? null,
            'embed_url' => $validated['source_type'] === 'external' ? $validated['embed_url'] : null,
        ];

        if ($validated['source_type'] === 'upload' && $request->hasFile('file')) {
            $upload = $this->fileUploadService->upload(
                $request->file('file'),
                'interviews/media',
                $existing?->file_url,
                'public'
            );
            $data['file_url'] = $upload['url'];
        } elseif ($validated['source_type'] === 'external') {
            // External media supersedes any previously-uploaded file.
            $data['file_url'] = null;
        }

        if ($request->hasFile('thumbnail')) {
            $upload = $this->fileUploadService->upload(
                $request->file('thumbnail'),
                'interviews/media/thumbnails',
                $existing?->thumbnail,
                'public'
            );
            $data['thumbnail'] = $upload['url'];
        }

        $interview->media()->updateOrCreate(
            ['interview_id' => $interview->id],
            $data
        );

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Interview media saved.')]);

        return back();
    }

    public function destroy(Interview $interview, InterviewMedia $medium)
    {
        abort_unless($medium->interview_id === $interview->id, 404);

        $medium->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Interview media removed.')]);

        return back();
    }
}
