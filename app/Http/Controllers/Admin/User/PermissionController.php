<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PermissionController extends Controller
{
    public function index(): Response
    {
        $permissions = Permission::query()
            ->paginate(20);
        return Inertia::render('Admin/Users/Permissions/Index', [
            'permissions' => $permissions,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Users/Permissions/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:permissions,slug'],
            'description' => ['nullable', 'string'],
        ]);

        Permission::create($validated);
        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('Permission created successfully.'),
        ]);
        return to_route('admin.permissions.index')
            ->with('success', 'Permission created successfully.');
    }

    public function edit(Permission $permission): Response
    {
        return Inertia::render('Admin/Users/Permissions/Edit', [
            'permission' => $permission,
        ]);
    }

    public function update(
        Request $request,
        Permission $permission
    ): RedirectResponse {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                'unique:permissions,slug,' . $permission->id,
            ],
            'description' => ['nullable', 'string'],
        ]);

        $permission->update($validated);
         Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('Permission updated successfully.'),
        ]);
        return to_route('admin.permissions.index')
            ->with('success', 'Permission updated successfully.');
    }

    public function destroy(Permission $permission): RedirectResponse
    {
        $permission->delete();
         Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('Permission deleted successfully.'),
        ]);
        return to_route('admin.permissions.index')
            ->with('success', 'Permission deleted successfully.');
    }
}
