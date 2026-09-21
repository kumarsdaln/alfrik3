<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MagazineIssueResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'magazine_id' => $this->magazine_id,

            'title' => $this->title,
            'slug' => $this->slug,
            'subtitle' => $this->subtitle,

            'volume' => $this->volume,
            'issue_number' => $this->issue_number,
            'cover_date' => $this->cover_date,

            'published_at' => $this->published_at,

            'description' => $this->description,
            'editor' => $this->editor,

            'download_count' => $this->download_count,

            'status' => $this->status->option(),

            'featured' => $this->featured,

            'magazine' => $this->whenLoaded('magazine', function () {
                return [
                    'id' => $this->magazine->id,
                    'title' => $this->magazine->title,
                    'slug' => $this->magazine->slug,
                ];
            }),

            'articles' => MagazineArticleResource::collection(
                $this->whenLoaded('articles')
            ),

            'media' => MediaResource::collection(
                $this->whenLoaded('media')
            ),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}