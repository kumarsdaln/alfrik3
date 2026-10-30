<?php

namespace App\Http\Resources\Role;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
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

            'slug' => $this->slug,

            'description' => $this->description,

            'users_count' => $this->when(
                isset($this->users_count),
                $this->users_count,
            ),

            'permissions_count' => $this->when(
                isset($this->permissions_count),
                $this->permissions_count,
            ),

            'users' => $this->whenLoaded(
                'users',
                fn() => $this->users
                    ->map(fn($user) => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'username' => $user->username,
                        'email' => $user->email,
                    ])
                    ->values()
                    ->all(),
            ),

            'permissions' => $this->whenLoaded(
                'permissions',
                fn() => $this->permissions
                    ->map(fn($permission) => [
                        'id' => $permission->id,
                        'name' => $permission->name,
                        'slug' => $permission->slug,
                    ])
                    ->values()
                    ->all(),
            ),

            'created_at' => $this->created_at,

            'updated_at' => $this->updated_at,
        ];
    }
}
