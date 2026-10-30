<?php

namespace App\Http\Resources\Research;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResearchSourceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'research_id' => $this->research_id,

            'title' => $this->title,

            'source_type' => $this->source_type->option(),

            'author' => $this->author,
            'publisher' => $this->publisher,

            'url' => $this->url,

            'published_at' =>
            $this->published_at?->toDateString(),

            'citation' => $this->citation,
            'description' => $this->description,

            'position' => $this->position,

            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
