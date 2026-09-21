<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SeoMetadataResource extends JsonResource
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

            'title' => $this->title,
            'description' => $this->description,

            'canonical_url' => $this->canonical_url,

            'indexable' => $this->indexable,
            'followable' => $this->followable,

            'og_title' => $this->og_title,
            'og_description' => $this->og_description,
            'og_type' => $this->og_type,
            'og_image_url' => $this->og_image_url,

            'twitter_card' => $this->twitter_card,
            'twitter_title' => $this->twitter_title,
            'twitter_description' => $this->twitter_description,
            'twitter_image_url' => $this->twitter_image_url,

            'locale' => $this->locale,
            'schema_type' => $this->schema_type,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
