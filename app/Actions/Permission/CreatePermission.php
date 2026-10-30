<?php

namespace App\Actions\Permission;

use App\Models\Permission;

class CreatePermission
{
    public function handle(array $data): Permission
    {
        return Permission::create($data);
    }
}