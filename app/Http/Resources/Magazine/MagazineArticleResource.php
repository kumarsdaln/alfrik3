<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MagazineArticleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'issue_id' => $this->issue_id,
            'author_id' => $this->author_id,

            'title' => $this->title,
            'slug' => $this->slug,
            'subtitle' => $this->subtitle,
            'excerpt' => $this->excerpt,
            'content' => $this->content,

            'type' => $this->type->option(),

            'byline' => $this->byline,
            'position' => $this->position,

            'featured' => $this->featured,

            'status' => $this->status->option(),

            'published_at' => $this->published_at,

            'views' => $this->views,
            'reading_time' => $this->reading_time,

            'issue' =>  $this->whenLoaded('issue', function () {
                return [
                    'id' => $this->issue->id,
                    'title' => $this->issue->title,
                    'slug' => $this->issue->slug,
                ];
            }),

            'author' => $this->whenLoaded('author', function () {
                return [
                    'id' => $this->author->id,
                    'name' => $this->author->name,
                    'username' => $this->author->username,
                    'avatar' => $this->author->avatar,
                ];
            }),

            'media' => MediaResource::collection(
                $this->whenLoaded('media')
            ),

            'categories' => CategoryResource::collection(
                $this->whenLoaded('categories')
            ),

            'tags' => TagResource::collection(
                $this->whenLoaded('tags')
            ),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}