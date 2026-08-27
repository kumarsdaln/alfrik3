<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RoleController extends Controller
{
    public function index(): Response
    {
        $roles = Role::query()
            ->withCount(['users', 'permissions'])
            ->latest()
            ->paginate(20);
        return Inertia::render('Admin/Users/Roles/Index', [
            'roles' => $roles,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Users/Roles/Create', [
            'permissions' => Permission::query()
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:roles,slug'],
            'description' => ['nullable', 'string'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['integer', 'exists:permissions,id'],
        ]);

        $permissionIds = $validated['permissions'] ?? [];

        unset($validated['permissions']);

        $role = Role::create($validated);

        $role->permissions()->sync($permissionIds);

        return to_route('admin.roles.index')
            ->with('success', 'Role created successfully.');
    }

    public function edit(Role $role): Response
    {
        $role->load('permissions');

        return Inertia::render('Admin/Users/Roles/Edit', [
            'role' => $role,
            'permissions' => Permission::query()
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function update(
        Request $request,
        Role $role
    ): RedirectResponse {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                'unique:roles,slug,' . $role->id,
            ],
            'description' => ['nullable', 'string'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['integer', 'exists:permissions,id'],
        ]);

        $permissionIds = $validated['permissions'] ?? [];

        unset($validated['permissions']);

        $role->update($validated);

        $role->permissions()->sync($permissionIds);

        return to_route('admin.roles.index')
            ->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        $role->permissions()->detach();

        $role->delete();

        return to_route('admin.roles.index')
            ->with('success', 'Role deleted successfully.');
    }
}
