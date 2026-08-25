<?php

namespace App\Services;

use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GoogleDriveService
{
    /**
     * Upload a file to Google Drive
     */
    public function upload(UploadedFile $file): array
    {
        $user = Auth::user();
        $client = GoogleClientFactory::drive($user);
        $service = new Drive($client);

        $fileName = time() . '_' . $file->getClientOriginalName();

        $driveFile = new DriveFile([
            'name' => $fileName,
        ]);

        $createdFile = $service->files->create($driveFile, [
            'data' => file_get_contents($file->getRealPath()),
            'mimeType' => $file->getMimeType(),
            'uploadType' => 'multipart',
            'fields' => 'id, name, webViewLink',
        ]);

        /* ------------------------
            MAKE FILE PUBLIC
        ------------------------ */
        $service->permissions->create(
            $createdFile->id,
            new Drive\Permission([
                'type' => 'anyone',
                'role' => 'reader',
            ])
        );

        return [
            'id' => $createdFile->id,
            'name' => $createdFile->name,
            'link' => $createdFile->webViewLink,
            'thumbnail' => 'https://drive.google.com/thumbnail?id=' . $createdFile->id,
        ];
    }

    /**
     * Delete a file from Google Drive
     */
    public function delete(string $url): bool
    {
        try {
            $fileId = $this->extractFileId($url);
            if (!$fileId) {
                Log::warning("Google Drive delete skipped: could not parse file id from URL.");
                return false;
            }
            $client = GoogleClientFactory::drive(Auth::user());
            $service = new Drive($client);

            $service->files->delete(trim($fileId));
            return true;
        } catch (\Exception $e) {
            Log::error("Google Drive delete failed: {$e->getMessage()}");
            return false;
        }
    }

    private function extractFileId(string $url): ?string
    {
        if (preg_match('~thumbnail\\?id=([^&]+)~', $url, $matches)) {
            return $matches[1];
        }

        if (preg_match('~(?:/d/|id=)([A-Za-z0-9_-]+)~', $url, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
