<?php

namespace App\Services\Storage;

use Illuminate\Http\UploadedFile;

interface StorageService
{
    public function store(
        UploadedFile $file,
        string $directory = 'media'
    ): array;

    public function delete(
        string $path,
        string $disk
    ): bool;

    public function exists(
        string $path,
        string $disk
    ): bool;

    public function url(
        string $path,
        string $disk
    ): string;
}
