<?php

namespace App\Http\Controllers\Admin\User;

use App\Actions\User\CreateUser;
use App\Actions\User\DeleteUser;
use App\Actions\User\UpdateUser;
use App\Actions\User\UpdateUserStatus;
use App\Enums\User\UserStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\User\UpdateUserStatusRequest;
use App\Http\Resources\User\UserResource;
use App\Models\Country;
use App\Models\Position;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request): Response|JsonResponse
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
                            ->orWhere('email', 'ilike', "%{$search}%")
                            ->orWhere('username', 'ilike', "%{$search}%");
                    });
                }
            )
            ->when(
                $request->has('status') && $request->status !== '',
                fn($query) => $query->where(
                    'is_active',
                    (bool) $request->integer('status')
                )
            )
            ->when(
                $request->filled('sort'),
                function ($query) use ($request) {
                    $allowedSorts = [
                        'name',
                        'email',
                        'username',
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
            ->when(
                ! $request->filled('sort'),
                fn($query) => $query->latest('id')
            )
            ->paginate(
                $request->integer('per_page', 15)
            )
            ->withQueryString();

        $stats = [
            'total' => User::count(),
            'active' => User::where('is_active', true)->count(),
            'inactive' => User::where('is_active', false)->count(),
        ];

        if ($request->expectsJson()) {
            return response()->json($users);
        }

        return Inertia::render('admin/user/Index', [
            'users' => UserResource::collection($users),
            'statusOptions' => UserStatus::dropdown(),
            'stats' => $stats,
            'filters' => [
                'search' => $request->string('search')->toString(),
                'status' => $request->string('status')->toString(),
                'sort' => $request->string('sort')->toString(),
                'direction' => $request->string('direction')->toString(),
            ],
        ]);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): Response
    {
        return Inertia::render('admin/user/Create', [
            'roleOptions' => self::roleOptions(),
            'countryOptions' => self::countryOptions(),
            'positionOptions' => self::positionOptions(),
        ]);
    }

    /**
     * Store a newly created user.
     */
    public function store(
        StoreUserRequest $request,
        CreateUser $action,
    ): RedirectResponse {
        $user = $action->handle(
            $request->validated()
        );

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('User created successfully.'),
        ]);

        return to_route(
            'admin.users.index',
            $user
        );
    }

    /**
     * Display the specified user.
     */
    public function show(User $user): Response
    {
        $user->load([
            'roles',
        ]);

        return Inertia::render('admin/user/Show', [
            'user' => new UserResource($user),
        ]);
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): Response
    {
        $user->load([
            'roles',
        ]);

        return Inertia::render('admin/user/Edit', [
            'user' => new UserResource($user),
            'roleOptions' => self::roleOptions(),
            'countryOptions' => self::countryOptions(),
            'positionOptions' => self::positionOptions(),
        ]);
    }

    /**
     * Update the specified user.
     */
    public function update(
        UpdateUserRequest $request,
        User $user,
        UpdateUser $updateUser,
    ): RedirectResponse {
        $updateUser->handle(
            user: $user,
            data: $request->validated(),
        );

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('User updated successfully.'),
        ]);

        return back();
    }

    /**
     * Update the status of the specified user.
     */
    public function updateStatus(
        UpdateUserStatusRequest $request,
        User $user,
        UpdateUserStatus $action,
    ): RedirectResponse {
        $action->handle(
            $user,
            $request->validated()
        );

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
        DeleteUser $action,
    ): RedirectResponse {
        $action->handle($user);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('User deleted successfully.'),
        ]);

        return to_route('admin.users.index');
    }

    /**
     * Get role options.
     */
    private static function roleOptions(): array
    {
        return Role::query()
            ->orderBy('name')
            ->get([
                'id',
                'name',
            ])
            ->map(fn(Role $role) => [
                'value' => $role->id,
                'label' => $role->name,
            ])
            ->values()
            ->all();
    }

    /**
     * Get country options.
     */
    private static function countryOptions(): array
    {
        return Country::query()
            ->orderBy('name')
            ->get([
                'id',
                'name',
            ])
            ->map(fn(Country $country) => [
                'value' => $country->id,
                'label' => $country->name,
            ])
            ->values()
            ->all();
    }

    /**
     * Get position options.
     */
    private static function positionOptions(): array
    {
        return Position::query()
            ->orderBy('name')
            ->get([
                'id',
                'name',
            ])
            ->map(fn(Position $position) => [
                'value' => $position->id,
                'label' => $position->name,
            ])
            ->values()
            ->all();
    }
}
