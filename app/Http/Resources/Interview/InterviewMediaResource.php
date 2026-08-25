<?php

namespace App\Http\Resources\Interview;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InterviewMediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'media_type' => $this->media_type,
            'source_type' => $this->source_type,
            'file_url' => $this->file_url,
            'embed_url' => $this->embed_url,
            'thumbnail' => $this->thumbnail,
            'duration' => $this->duration,
        ];
    }
}
