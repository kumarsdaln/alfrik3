<?php

namespace App\Http\Resources\Report;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'research_id' => $this->research_id,

            'title' => $this->title,

            'slug' => $this->slug,

            'subtitle' => $this->subtitle,

            'description' => $this->description,

            'summary' => $this->summary,

            'type' => [
                'value' => $this->type->value,
                'label' => $this->type->label(),
                'color' => $this->type->color(),
            ],

            'status' => [
                'value' => $this->status->value,
                'label' => $this->status->label(),
                'color' => $this->status->color(),
            ],

            'author_id' => $this->author_id,

            'author' => $this->whenLoaded(
                'author',
                fn () => [
                    'id' => $this->author->id,
                    'name' => $this->author->name,
                ]
            ),

            'featured' => $this->featured,

            'published_at' => $this->published_at?->toISOString(),

            'report_date' => $this->report_date?->toISOString(),

            'created_at' => $this->created_at?->toISOString(),

            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}