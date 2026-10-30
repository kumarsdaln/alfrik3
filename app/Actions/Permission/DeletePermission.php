<?php

namespace App\Actions\Permission;

use App\Models\Permission;

class DeletePermission
{
    public function handle(Permission $permission): void
    {
        $permission->delete();
    }
}