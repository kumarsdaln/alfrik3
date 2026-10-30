<?php

namespace App\Http\Resources\Research;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResearchQuestionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'research_id' => $this->research_id,

            'question' => $this->question,
            'description' => $this->description,

            'type' => $this->type->option(),

            'position' => $this->position,

            'findings' => ResearchFindingResource::collection(
                $this->whenLoaded('findings')
            ),

            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}