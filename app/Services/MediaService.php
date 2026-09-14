<?php

namespace App\Services;

use App\Models\Media;
use App\Services\Storage\StorageService;
use Illuminate\Http\UploadedFile;

class MediaService
{
    public function __construct(
        private readonly StorageService $storageService,
    ) {}

    public function upload(
        UploadedFile $file,
        string $directory = 'media',
        ?string $alt = null,
        ?array $metadata = null,
    ): Media 
    {
        $stored = $this->storageService->store(
            $file,
            $directory
        );

        return Media::create([
            'name' => $file->getClientOriginalName(),
            'file_name' => $stored['file_name'],
            'mime_type' => $file->getMimeType(),
            'extension' => $file->getClientOriginalExtension(),
            'size' => $file->getSize(),
            'disk' => $stored['disk'],
            'path' => $stored['path'],
            'alt' => $alt,
            'metadata' => $metadata,
        ]);
    }

    public function delete(Media $media): bool
    {
        $deleted = $this->storageService->delete(
            $media->path,
            $media->disk
        );

        if ($deleted) {
            $media->delete();
        }

        return $deleted;
    }

    public function url(Media $media): string
    {
        return $this->storageService->url(
            $media->path,
            $media->disk
        );
    }
}
