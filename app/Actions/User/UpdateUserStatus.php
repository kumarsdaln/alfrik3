<?php

namespace App\Actions\User;

use App\Models\User;

class UpdateUserStatus
{
    public function handle(
        User $user,
        array $data,
    ): User {
        $user->update([
            'is_active' => $data['is_active'],
        ]);

        return $user->refresh();
    }
}
