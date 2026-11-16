<?php

namespace App\Http\Resources\Research;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\ContentParticipant\ParticipantResource;
use App\Http\Resources\TagResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResearchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            /*
            |--------------------------------------------------------------------------
            | Research
            |--------------------------------------------------------------------------
            */

            'id' => $this->id,

            'title' => $this->title,
            'slug' => $this->slug,
            'subtitle' => $this->subtitle,
            'description' => $this->description,
            'summary' => $this->summary,

            'type' => $this->type->option(),

            'status' => $this->status->option(),

            'participants' => $this->whenLoaded(
                'participants',
                fn () => ParticipantResource::collection($this->participants)
            ),

            'categories' => $this->whenLoaded(
                'categories',
                fn () => CategoryResource::collection($this->categories)
            ),

            'tags' => $this->whenLoaded(
                'tags',
                fn () => TagResource::collection($this->tags)
            ),

            'featured' => $this->featured,

            'published_at' =>
                $this->published_at?->toISOString(),

            /*
            |--------------------------------------------------------------------------
            | Research Relationships
            |--------------------------------------------------------------------------
            */

            'methodology' => new ResearchMethodologyResource(
                $this->whenLoaded('methodology')
            ),

            'sources' => ResearchSourceResource::collection(
                $this->whenLoaded('sources')
            ),

            'questions' => ResearchQuestionResource::collection(
                $this->whenLoaded('questions')
            ),

            'findings' => ResearchFindingResource::collection(
                $this->whenLoaded('findings')
            ),

            'evidence' => ResearchEvidenceResource::collection(
                $this->whenLoaded('evidence')
            ),

            'members' => ResearchMemberResource::collection(
                $this->whenLoaded('members')
            ),

            /*
            |--------------------------------------------------------------------------
            | Timestamps
            |--------------------------------------------------------------------------
            */

            'created_at' =>
                $this->created_at?->toISOString(),

            'updated_at' =>
                $this->updated_at?->toISOString(),
        ];
    }
}