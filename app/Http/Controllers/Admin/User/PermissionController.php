<?php

namespace App\Http\Controllers\Admin\User;

use App\Actions\Permission\CreatePermission;
use App\Actions\Permission\DeletePermission;
use App\Actions\Permission\UpdatePermission;
use App\Http\Controllers\Controller;
use App\Http\Requests\Permission\StorePermissionRequest;
use App\Http\Requests\Permission\UpdatePermissionRequest;
use App\Http\Resources\Permission\PermissionResource;
use App\Models\Permission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PermissionController extends Controller
{
    public function index(Request $request): Response
    {
        $permissions = Permission::query()
            ->withCount('roles')
            ->when(
                $request->filled('search'),
                fn($query) => $query->where(function ($query) use ($request) {
                    $query
                        ->where(
                            'name',
                            'ilike',
                            '%' . $request->search . '%',
                        )
                        ->orWhere(
                            'slug',
                            'ilike',
                            '%' . $request->search . '%',
                        );
                })
            )
            ->latest()
            ->paginate(
                $request->integer('per_page', 15),
            )
            ->withQueryString();

        return Inertia::render(
            'admin/user/permission/Index',
            [
                'permissions' => PermissionResource::collection(
                    $permissions,
                ),

                'filters' => [
                    'search' => $request->search,
                ],
            ],
        );
    }

    public function create(): Response
    {
        return Inertia::render(
            'admin/user/permission/Create',
        );
    }

    public function store(
        StorePermissionRequest $request,
        CreatePermission $createPermission,
    ): RedirectResponse {
        $permission = $createPermission->handle(
            data: $request->validated(),
        );

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('Permission created successfully.'),
        ]);

        return to_route(
            'admin.permissions.edit',
            $permission,
        );
    }

    public function show(
        Permission $permission,
    ): Response {
        $permission->load('roles');

        return Inertia::render(
            'admin/user/permission/Show',
            [
                'permission' => new PermissionResource(
                    $permission,
                ),
            ],
        );
    }

    public function edit(
        Permission $permission,
    ): Response {
        $permission->load('roles');

        return Inertia::render(
            'admin/user/permission/Edit',
            [
                'permission' => new PermissionResource(
                    $permission,
                ),
            ],
        );
    }

    public function update(
        UpdatePermissionRequest $request,
        Permission $permission,
        UpdatePermission $updatePermission,
    ): RedirectResponse {
        $updatePermission->handle(
            permission: $permission,
            data: $request->validated(),
        );

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('Permission updated successfully.'),
        ]);

        return to_route(
            'admin.permissions.index',
        );
    }

    public function destroy(
        Permission $permission,
        DeletePermission $deletePermission,
    ): RedirectResponse {
        $deletePermission->handle(
            permission: $permission,
        );

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('Permission deleted successfully.'),
        ]);

        return to_route(
            'admin.permissions.index',
        );
    }
}
