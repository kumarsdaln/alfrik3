<?php

namespace App\Http\Resources\User;

use App\Enums\User\UserStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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

            'email' => $this->email,

            'avatar' => $this->avatar,

            'headline' => $this->headline,

            'country_id' => $this->country_id,

            'position_id' => $this->position_id,

            'is_active' => (bool) $this->is_active,

            'status' => UserStatus::from(
                (int) $this->is_active
            )->option(),

            'roles' => $this->whenLoaded(
                'roles',
                fn () => $this->roles
                    ->map(fn ($role) => [
                        'id' => $role->id,
                        'name' => $role->name,
                    ])
                    ->values()
                    ->all()
            ),

            'created_at' => $this->created_at,

            'updated_at' => $this->updated_at,
        ];
    }
}