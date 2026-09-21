<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'collection' => $this->collection,

            'name' => $this->name,
            'file_name' => $this->file_name,

            'mime_type' => $this->mime_type,
            'extension' => $this->extension,

            'size' => $this->size,

            'disk' => $this->disk,
            'path' => $this->path,

            'url' => $this->url(),

            'alt' => $this->alt,

            'metadata' => $this->metadata,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
