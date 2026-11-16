<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'native' => $this->native,
            'phone' => $this->phone,
            'continent_code' => $this->continent_code,
            'capital' => $this->capital,
            'currency' => $this->currency,
            'languages' => $this->languages,

            'continent' => $this->whenLoaded('continent', fn () => [
                'id' => $this->continent?->id,
                'name' => $this->continent?->name,
                'code' => $this->continent?->code,
            ]),

            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}