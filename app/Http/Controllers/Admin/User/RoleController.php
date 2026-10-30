<?php

namespace App\Http\Controllers\Admin\User;

use App\Actions\Role\CreateRole;
use App\Actions\Role\DeleteRole;
use App\Actions\Role\UpdateRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Http\Resources\Role\RoleResource;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RoleController extends Controller
{
    public function index(Request $request): Response
    {
        $roles = Role::query()
            ->withCount([
                'users',
                'permissions',
            ])
            ->when(
                $request->filled('search'),
                fn ($query) => $query->where(function ($query) use ($request) {
                    $query
                        ->where('name', 'ilike', '%' . $request->search . '%')
                        ->orWhere('slug', 'ilike', '%' . $request->search . '%');
                })
            )
            ->latest()
            ->paginate(
                $request->integer('per_page', 15)
            )
            ->withQueryString();

        return Inertia::render('admin/user/role/Index', [
            'roles' => RoleResource::collection($roles),

            'filters' => [
                'search' => $request->search,
            ],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/user/role/Create', [
            'permissionOptions' => self::permissionOptions(),
        ]);
    }

    public function store(
        StoreRoleRequest $request,
        CreateRole $createRole,
    ): RedirectResponse {
        $role = $createRole->handle(
            data: $request->validated(),
        );

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('Role created successfully.'),
        ]);

        return to_route(
            'admin.roles.edit',
            $role,
        );
    }

    public function show(Role $role): Response
    {
        $role->load([
            'permissions',
        ]);

        return Inertia::render('admin/user/role/Show', [
            'role' => new RoleResource($role),
        ]);
    }

    public function edit(Role $role): Response
    {
        $role->load([
            'permissions',
        ]);

        return Inertia::render('admin/user/role/Edit', [
            'role' => new RoleResource($role),

            'permissionOptions' => self::permissionOptions(),
        ]);
    }

    public function update(
        UpdateRoleRequest $request,
        Role $role,
        UpdateRole $updateRole,
    ): RedirectResponse {
        $updateRole->handle(
            role: $role,
            data: $request->validated(),
        );

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('Role updated successfully.'),
        ]);

        return to_route(
            'admin.roles.index',
        );
    }

    public function destroy(
        Role $role,
        DeleteRole $deleteRole,
    ): RedirectResponse {
        $deleteRole->handle(
            role: $role,
        );

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('Role deleted successfully.'),
        ]);

        return to_route(
            'admin.roles.index',
        );
    }

    private static function permissionOptions(): array
    {
        return Permission::query()
            ->orderBy('name')
            ->get([
                'id',
                'name',
            ])
            ->map(fn (Permission $permission) => [
                'value' => $permission->id,
                'label' => $permission->name,
            ])
            ->values()
            ->all();
    }
}