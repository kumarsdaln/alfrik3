<?php

namespace App\Actions\Role;

use App\Models\Role;

class UpdateRole
{
    public function handle(
        Role $role,
        array $data,
    ): Role {
        $permissions = $data['permissions'] ?? [];

        unset($data['permissions']);

        $role->update($data);

        $role->permissions()->sync($permissions);

        return $role;
    }
}
