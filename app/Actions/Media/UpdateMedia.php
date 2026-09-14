<?php

namespace App\Actions\Media;

use App\Models\Media;
use Illuminate\Support\Facades\DB;

class UpdateMedia
{
    public function handle(
        Media $media,
        array $data,
    ): Media {
        return DB::transaction(function () use ($media, $data) {
            $media->update([
                'collection' => $data['collection'] ?? $media->collection,
                'name' => $data['name'] ?? $media->name,
                'alt' => $data['alt'] ?? $media->alt,
                'metadata' => $data['metadata'] ?? $media->metadata,
            ]);

            return $media->refresh();
        });
    }
}