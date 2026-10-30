<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateUser
{
    public function handle(array $data): User
    {
        $roles = $data['roles'] ?? [];

        unset($data['roles']);

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        $user->roles()->sync($roles);

        return $user->refresh();
    }
}
