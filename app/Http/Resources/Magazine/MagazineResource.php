<?php

namespace App\Http\Resources\Magazine;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\MediaResource;
use App\Http\Resources\TagResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MagazineResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'title' => $this->title,
            'slug' => $this->slug,
            'subtitle' => $this->subtitle,
            'description' => $this->description,

            'status' => $this->status->option(),

            'featured' => $this->featured,

            'published_at' => $this->published_at,

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