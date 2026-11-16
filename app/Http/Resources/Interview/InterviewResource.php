<?php

namespace App\Http\Resources\Interview;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\ContentParticipant\ParticipantResource;
use App\Http\Resources\MediaResource;
use App\Http\Resources\SeoMetadataResource;
use App\Http\Resources\TagResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InterviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            // Publication details
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'introduction' => $this->introduction,
            'conclusion' => $this->conclusion,

            // Publication settings
            'interview_type' => $this->interview_type?->option(),
            'status' => $this->status?->option(),
            'published_at' => $this->published_at,

            // Participants
            'participants' => ParticipantResource::collection(
                $this->whenLoaded('participants'),
            ),

            // Interview conversation
            'questions' => InterviewQuestionResource::collection(
                $this->whenLoaded('questions'),
            ),

            // Taxonomy
            'categories' => CategoryResource::collection(
                $this->whenLoaded('categories'),
            ),

            'tags' => TagResource::collection(
                $this->whenLoaded('tags'),
            ),

            // Media
            'media' => MediaResource::collection(
                $this->whenLoaded('media'),
            ),

            // SEO
            // SEO
            'seo' => $this->whenLoaded(
                'seo',
                fn() => $this->seo
                    ? SeoMetadataResource::make($this->seo)
                    : null,
            ),
        ];
    }
}
