<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdateUser
{
    public function handle(
        User $user,
        array $data,
    ): User {
        $roles = $data['roles'] ?? [];

        unset(
            $data['roles'],
            $data['password_confirmation'],
        );

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        $user->roles()->sync($roles);

        return $user;
    }
}