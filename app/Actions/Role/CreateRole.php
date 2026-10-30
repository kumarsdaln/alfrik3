<?php

namespace App\Actions\Role;

use App\Models\Role;

class CreateRole
{
    public function handle(array $data): Role
    {
        $permissions = $data['permissions'] ?? [];

        unset($data['permissions']);

        $role = Role::create($data);

        $role->permissions()->sync($permissions);

        return $role;
    }
}