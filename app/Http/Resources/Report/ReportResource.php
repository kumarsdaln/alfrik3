<?php

namespace App\Http\Resources\Report;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\ContentParticipant\ParticipantResource;
use App\Http\Resources\MediaResource;
use App\Http\Resources\SeoMetadataResource;
use App\Http\Resources\TagResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'subtitle' => $this->subtitle,
            'description' => $this->description,
            'summary' => $this->summary,

            'type' => $this->type->option(),
            'status' => $this->status->option(),

            'featured' => $this->featured,
            'published_at' => $this->published_at?->toISOString(),
            'report_date' => $this->report_date?->toISOString(),

            'sections' => ReportSectionResource::collection(
                $this->whenLoaded('sections')
            ),

            'participants' => ParticipantResource::collection(
                $this->whenLoaded('participants')
            ),

            'media' => MediaResource::collection(
                $this->whenLoaded('media')
            ),

            'categories' => CategoryResource::collection(
                $this->whenLoaded('categories')
            ),

            'tags' => TagResource::collection(
                $this->whenLoaded('tags')
            ),

            'seo' => $this->whenLoaded(
                'seo',
                fn () => new SeoMetadataResource($this->seo)
            ),

            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}