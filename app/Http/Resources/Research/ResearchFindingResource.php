<?php

namespace App\Http\Resources\Research;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResearchFindingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'research_id' => $this->research_id,

            'title' => $this->title,
            'summary' => $this->summary,
            'description' => $this->description,

            'type' => $this->type->option(),

            'confidence' => $this->confidence,

            'position' => $this->position,

            'evidence' => ResearchEvidenceResource::collection(
                $this->whenLoaded('evidence')
            ),

            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}