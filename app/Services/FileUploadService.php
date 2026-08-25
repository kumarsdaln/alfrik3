<?php

namespace App\Services;

use App\Exceptions\GoogleDriveNotConnectedException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

class FileUploadService
{
    protected string $defaultDisk;
    protected GoogleDriveService $driveService;

    public function __construct(GoogleDriveService $driveService)
    {
        $this->defaultDisk = config('filesystems.default', 'public');
        $this->driveService = $driveService;
    }

    /**
     * Upload file
     */
    public function upload(
        UploadedFile $file,
        string $folder,
        ?string $oldFile = null,
        ?string $disk = null
    ): array {
        $disk = $disk ?? $this->defaultDisk;

        // Delete old file safely
        if ($oldFile) {
            $this->delete($oldFile, $disk);
        }

        // Generate unique filename
        $filename = $this->generateFileName($file);

        // Store file
        $path = $file->storeAs($folder, $filename, $disk);

        return [
            'path' => $path,
            'url' => Storage::disk($disk)->url($path),
            'provider' => 'local',
            'disk' => $disk,
            'size' => $file->getSize(),
            'mime' => $file->getMimeType(),
            'original_name' => $file->getClientOriginalName(),
        ];
    }

    /**
     * Upload file to local storage or Google Drive
     */
    public function uploadTo(
        UploadedFile $file,
        string $folder,
        string $destination = 'local',
        ?string $oldFile = null,
        ?string $disk = null
    ): array {
        if ($destination === 'drive') {
            try {
                if ($oldFile) {
                    $this->deleteAny($oldFile, $disk);
                }

                $uploaded = $this->driveService->upload($file);
                $isImage = str_starts_with($file->getMimeType(), 'image/');
                $url = $isImage
                    ? 'https://drive.google.com/thumbnail?id=' . $uploaded['id'] . '&sz=s1200'
                    : $uploaded['link'];

                return [
                    'url' => $url,
                    'provider' => 'drive',
                    'id' => $uploaded['id'],
                    'size' => $file->getSize(),
                    'mime' => $file->getMimeType(),
                    'original_name' => $file->getClientOriginalName(),
                ];
            } catch (GoogleDriveNotConnectedException $e) {
                throw $e;
            }
        }

        return $this->upload($file, $folder, $oldFile, $disk);
    }

    /**
     * Delete file
     */
    public function delete(?string $fileUrl, ?string $disk = null): bool
    {
        if (!$fileUrl) return false;

        $disk = $disk ?? $this->defaultDisk;

        try {
            $path = $this->extractPathFromUrl($fileUrl, $disk);

            if (Storage::disk($disk)->exists($path)) {
                return Storage::disk($disk)->delete($path);
            }

            return false;
        } catch (\Throwable $e) {
            report($e);
            return false;
        }
    }

    /**
     * Delete file from Google Drive or local storage
     */
    public function deleteAny(?string $fileUrl, ?string $disk = null): bool
    {
        if (!$fileUrl) return false;

        if (str_contains($fileUrl, 'drive.google.com')) {
            return $this->driveService->delete($fileUrl);
        }

        return $this->delete($fileUrl, $disk);
    }

    /**
     * Replace file (upload + delete old)
     */
    public function replace(
        UploadedFile $file,
        string $folder,
        ?string $oldFile = null,
        ?string $disk = null
    ): array {
        return $this->upload($file, $folder, $oldFile, $disk);
    }

    /**
     * Generate unique file name
     */
    protected function generateFileName(UploadedFile $file): string
    {
        return now()->timestamp . '_' . Str::uuid() . '.' . $file->getClientOriginalExtension();
    }

    /**
     * Extract storage path from URL
     */
    protected function extractPathFromUrl(string $url, string $disk): string
    {
        $baseUrl = Storage::disk($disk)->url('');

        return ltrim(str_replace($baseUrl, '', $url), '/');
    }

    /**
     * Check if file exists
     */
    public function exists(string $fileUrl, ?string $disk = null): bool
    {
        $disk = $disk ?? $this->defaultDisk;

        $path = $this->extractPathFromUrl($fileUrl, $disk);

        return Storage::disk($disk)->exists($path);
    }
}
