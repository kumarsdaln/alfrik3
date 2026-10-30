<?php

namespace App\Http\Resources\Research;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResearchMethodologyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'research_id' => $this->research_id,

            'method' => $this->method,
            'description' => $this->description,

            'research_design' => $this->research_design,
            'data_collection_method' => $this->data_collection_method,

            'sample_size' => $this->sample_size,

            'population' => $this->population,
            'geography' => $this->geography,

            'start_date' => $this->start_date?->toDateString(),
            'end_date' => $this->end_date?->toDateString(),

            'limitations' => $this->limitations,

            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}