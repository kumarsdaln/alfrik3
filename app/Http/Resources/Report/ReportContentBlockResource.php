<?php

namespace App\Http\Resources\Report;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportContentBlockResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'report_section_id' => $this->report_section_id,

            'type' => [
                'value' => $this->type->value,
                'label' => $this->type->label(),
            ],

            'title' => $this->title,

            'description' => $this->description,

            'content' => $this->content,

            'position' => $this->position,

            'created_at' => $this->created_at?->toISOString(),

            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}