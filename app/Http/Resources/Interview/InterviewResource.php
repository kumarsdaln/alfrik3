<?php

namespace App\Http\Resources\Interview;

use App\Http\Resources\CategoryResource;
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
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,

            'interview_type' => $this->interview_type?->option(),
            'status' => $this->status?->option(),

            'published_at' => $this->published_at,
            'views_count' => $this->views_count,

            'creator' => $this->whenLoaded(
                'creator',
                fn () => [
                    'id' => $this->creator->id,
                    'name' => $this->creator->name,
                    'email' => $this->creator->email,
                ],
            ),

            'participants' => InterviewParticipantResource::collection(
                $this->whenLoaded('participants'),
            ),

            'questions' => InterviewQuestionResource::collection(
                $this->whenLoaded('questions'),
            ),

            'categories' => CategoryResource::collection(
                $this->whenLoaded('categories'),
            ),

            'tags' => TagResource::collection(
                $this->whenLoaded('tags'),
            ),

            'media' => MediaResource::collection(
                $this->whenLoaded('media'),
            ),

            'seo' => SeoMetadataResource::collection(
                $this->whenLoaded('seo'),
            ),
        ];
    }
}