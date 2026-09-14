<?php

namespace App\Actions\Media;

use App\Models\Media;
use App\Services\Storage\StorageService;
use Illuminate\Support\Facades\DB;

class DeleteMedia
{
    public function __construct(
        protected StorageService $storage,
    ) {
    }

    public function handle(Media $media): void
    {
        $path = $media->path;
        $disk = $media->disk;

        DB::transaction(function () use ($media) {
            $media->delete();
        });

        $this->storage->delete($path, $disk);
    }
}