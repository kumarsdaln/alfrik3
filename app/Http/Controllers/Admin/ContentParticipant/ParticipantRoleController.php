<?php

namespace App\Http\Controllers\Admin\ContentParticipant;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContentParticipant\ParticipantRoleResource;
use App\Models\ParticipantRole;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class ParticipantRoleController extends Controller
{
    /**
     * Display all participant roles.
     */
    public function index(Request $request): Response
    {
        $roles = ParticipantRole::query()
            ->withCount('contentParticipants')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim($request->string('search')->toString());

                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where(
                    'is_active',
                    $request->input('status') === 'active'
                );
            })
            ->orderBy('position')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('admin/participant/role/Index', [
            'roles' => ParticipantRoleResource::collection($roles),
            'filters' => $request->only(['search', 'status']),
            'stats' => [
                'total' => ParticipantRole::count(),
                'active' => ParticipantRole::where('is_active', true)->count(),
                'inactive' => ParticipantRole::where('is_active', false)->count(),
            ],
        ]);
    }


    /**
     * Display the create form.
     */
    public function create(): Response
    {
        return Inertia::render('admin/participant/role/Create');
    }

    /**
     * Store a new participant role.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:participant_roles,name'],
            'slug' => [
                'required',
                'string',
                'max:100',
                'alpha_dash',
                'unique:participant_roles,slug',
            ],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['sometimes', 'boolean'],
            'position' => ['sometimes', 'integer', 'min:0'],
        ]);

        ParticipantRole::create([
            ...$validated,
            'is_active' => $validated['is_active'] ?? true,
            'position' => $validated['position'] ?? 0,
        ]);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('Participant role created successfully.'),
        ]);

        return to_route('admin.participants.roles.index');
    }

    /**
     * Display the edit form.
     */
    public function edit(ParticipantRole $participantRole): Response
    {
        return Inertia::render('admin/participant/role/Edit', [
            'role' => $participantRole,
        ]);
    }

    /**
     * Update an existing participant role.
     */
    public function update(
        Request $request,
        ParticipantRole $participantRole,
    ): RedirectResponse {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('participant_roles', 'name')
                    ->ignore($participantRole->id),
            ],
            'slug' => [
                'required',
                'string',
                'max:100',
                'alpha_dash',
                Rule::unique('participant_roles', 'slug')
                    ->ignore($participantRole->id),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['required', 'boolean'],
            'position' => ['required', 'integer', 'min:0'],
        ]);

        $participantRole->update($validated);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('Participant role updated successfully.'),
        ]);

        return to_route('admin.participants.roles.index');
    }

    /**
     * Delete a participant role if it is not in use.
     */
    public function destroy(
        ParticipantRole $participantRole,
    ): RedirectResponse {
        if ($participantRole->contentParticipants()->exists()) {
            throw ValidationException::withMessages([
                'role' => __(
                    'This role is assigned to participants. Deactivate it instead of deleting it.'
                ),
            ]);
        }

        $participantRole->delete();

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('Participant role deleted successfully.'),
        ]);

        return to_route('admin.participants.roles.index');
    }
}
