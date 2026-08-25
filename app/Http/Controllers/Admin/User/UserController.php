<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request): Response
    {
        $users = User::query()
            ->with('roles')
            ->when(
                $request->filled('search'),
                function ($query) use ($request) {
                    $search = $request->string('search')->trim();

                    $query->where(function ($query) use ($search) {
                        $query
                            ->where('name', 'ilike', "%{$search}%")
                            ->orWhere('email', 'ilike', "%{$search}%");
                    });
                }
            )
            ->when(
                $request->filled('status'),
                fn ($query) => $query->where(
                    'status',
                    $request->string('status')->toString()
                )
            )
            ->when(
                $request->filled('sort'),
                function ($query) use ($request) {
                    $allowedSorts = [
                        'name',
                        'email',
                        'created_at',
                        'updated_at',
                    ];

                    $sort = $request->string('sort')->toString();

                    if (! in_array($sort, $allowedSorts, true)) {
                        return;
                    }

                    $direction = $request
                        ->string('direction')
                        ->toString();

                    if (! in_array($direction, ['asc', 'desc'], true)) {
                        $direction = 'asc';
                    }

                    $query->orderBy($sort, $direction);
                }
            )
            ->latest('id')
            ->paginate(
                $request->integer('per_page', 15)
            )
            ->withQueryString();

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
        ]);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): Response
    {
        return Inertia::render('Admin/Users/Create');
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
            'status' => [
                'required',
                'string',
                'in:active,inactive',
            ],
        ]);

        $user = User::create($validated);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('User created successfully.'),
        ]);

        return to_route(
            'admin.users.show',
            $user
        );
    }

    /**
     * Display the specified user.
     */
    public function show(User $user): Response
    {
        $user->load('roles');

        return Inertia::render('Admin/Users/Show', [
            'user' => $user,
        ]);
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): Response
    {
        $user->load('roles');

        return Inertia::render('Admin/Users/Edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the specified user.
     */
    public function update(
        Request $request,
        User $user,
    ): RedirectResponse {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email,' . $user->id,
            ],
            'status' => [
                'required',
                'string',
                'in:active,inactive',
            ],
        ]);

        $user->update($validated);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('User updated successfully.'),
        ]);

        return to_route(
            'admin.users.show',
            $user
        );
    }

    /**
     * Update the status of the specified user.
     */
    public function updateStatus(
        Request $request,
        User $user,
    ): RedirectResponse {
        $validated = $request->validate([
            'status' => [
                'required',
                'string',
                'in:active,inactive',
            ],
        ]);

        $user->update([
            'status' => $validated['status'],
        ]);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('User status updated successfully.'),
        ]);

        return back();
    }

    /**
     * Remove the specified user.
     */
    public function destroy(
        User $user,
    ): RedirectResponse {
        $user->delete();

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('User deleted successfully.'),
        ]);

        return to_route('admin.users.index');
    }
}