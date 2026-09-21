<?php

namespace App\Http\Resources\Report;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportSectionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'report_id' => $this->report_id,

            'title' => $this->title,

            'subtitle' => $this->subtitle,

            'content' => $this->content,

            'position' => $this->position,

            'content_blocks' => $this->whenLoaded(
                'contentBlocks',
                fn () => ReportContentBlockResource::collection(
                    $this->contentBlocks
                ),
            ),

            'created_at' => $this->created_at?->toISOString(),

            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}