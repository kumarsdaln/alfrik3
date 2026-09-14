<?php

namespace App\Services\Storage;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LocalStorageService implements StorageService
{
    public function store(
        UploadedFile $file,
        string $directory = 'media'
    ): array {
        $disk = config('filesystems.default');

        $fileName = Str::uuid()->toString()
            . '.' . $file->getClientOriginalExtension();

        $path = $file->storeAs(
            $directory,
            $fileName,
            $disk
        );

        return [
            'disk' => $disk,
            'path' => $path,
            'file_name' => $fileName,
        ];
    }

    public function delete(
        string $path,
        string $disk
    ): bool {
        return Storage::disk($disk)->delete($path);
    }

    public function exists(
        string $path,
        string $disk
    ): bool {
        return Storage::disk($disk)->exists($path);
    }

    public function url(
        string $path,
        string $disk
    ): string {
        return Storage::disk($disk)->url($path);
    }
}
