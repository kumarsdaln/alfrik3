<?php

namespace App\Actions\Media;

use App\Models\Media;
use App\Services\Storage\StorageService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Throwable;

class CreateMedia
{
    public function __construct(
        protected StorageService $storage,
    ) {}

    public function handle(
        Model $model,
        UploadedFile $file,
        string $collection = 'default',
        ?string $name = null,
        ?string $alt = null,
        array $metadata = [],
        string $directory = 'media',
    ): Media {
        $stored = null;

        try {
            $stored = $this->storage->store(
                file: $file,
                directory: $directory,
            );

            return DB::transaction(function () use (
                $model,
                $file,
                $collection,
                $name,
                $alt,
                $metadata,
                $stored,
            ) {
                return $model->media()->create([
                    'collection' => $collection,

                    'name' => $name ?? pathinfo(
                        $file->getClientOriginalName(),
                        PATHINFO_FILENAME,
                    ),

                    'file_name' => $stored['file_name'],

                    'mime_type' => $file->getMimeType()
                        ?? $file->getClientMimeType(),

                    'extension' => $file->getClientOriginalExtension()
                        ?: $file->extension(),

                    'size' => $file->getSize(),

                    'disk' => $stored['disk'],
                    'path' => $stored['path'],

                    'alt' => $alt,
                    'metadata' => $metadata ?: null,
                ]);
            });
        } catch (Throwable $exception) {
            if ($stored !== null) {
                $this->storage->delete(
                    $stored['path'],
                    $stored['disk'],
                );
            }

            throw $exception;
        }
    }
}