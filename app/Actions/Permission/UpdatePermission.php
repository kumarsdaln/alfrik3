<?php

namespace App\Actions\Permission;

use App\Models\Permission;

class UpdatePermission
{
    public function handle(
        Permission $permission,
        array $data,
    ): Permission {
        $permission->update($data);

        return $permission;
    }
}