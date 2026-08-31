<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'avatar' => $this->avatar,
            'headline' => $this->headline,

            'position' => $this->whenLoaded('position', fn () => [
                'id' => $this->position?->id,
                'name' => $this->position?->name,
            ]),

            'country' => $this->whenLoaded('country', fn () => [
                'id' => $this->country?->id,
                'name' => $this->country?->name,
                'code' => $this->country?->code,
            ]),

            'languages' => $this->whenLoaded('languages', fn () =>
                $this->languages->map(fn ($language) => [
                    'id' => $language->id,
                    'name' => $language->name,
                    'native' => $language->native,
                    'code' => $language->code,
                ])
            ),

            'industries' => $this->whenLoaded('industries', fn () =>
                $this->industries->map(fn ($industry) => [
                    'id' => $industry->id,
                    'name' => $industry->name,
                ])
            ),
        ];
    }
}
