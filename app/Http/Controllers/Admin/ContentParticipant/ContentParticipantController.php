<?php

namespace App\Http\Controllers\Admin\ContentParticipant;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Models\ParticipantRole;
use App\Models\User;
use App\Support\ModelResolver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class ContentParticipantController extends Controller
{
    /**
     * Display participants assigned to a content item.
     */
    public function index(
        Request $request,
        string $type,
        int $contentId,
    ): Response {
        $content = $this->resolveContent($type, $contentId);

        // $this->authorize('update', $content);

        $participants = $content->participants()
            ->with([
                'user:id,name,email,username,avatar',
                'role:id,name,slug',
            ])
            ->orderBy('position')
            ->orderBy('id')
            ->get();

        $users = User::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim($request->string('search')->toString());

                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('admin/participant/Index', [
            'model' => [
                'id' => $content->getKey(),
                'type' => $type,
                'title' => $content->getAttribute('title')
                    ?? $content->getAttribute('name')
                    ?? class_basename($content),
            ],
            'participants' => $participants,
            'roles' => ParticipantRole::query()
                ->where('is_active', true)
                ->orderBy('position')
                ->orderBy('name')
                ->get(['id', 'name', 'slug', 'description']),
            'users' => UserResource::collection($users),
            'filters' => $request->only(['search']),
        ]);
    }

    /**
     * Assign an existing user to a content item.
     */
    public function store(
        Request $request,
        string $type,
        int $contentId,
    ): RedirectResponse {
        $content = $this->resolveContent($type, $contentId);

        // $this->authorize('update', $content);

        $validated = $request->validate([
            'user_id' => [
                'required',
                'integer',
                'exists:users,id',
                Rule::unique('content_participants', 'user_id')
                    ->where(fn($query) => $query
                        ->where(
                            'participantable_type',
                            $content->getMorphClass()
                        )
                        ->where(
                            'participantable_id',
                            $content->getKey()
                        )),
            ],

            'participant_role_id' => [
                'required',
                'integer',
                Rule::exists('participant_roles', 'id')
                    ->where('is_active', true),
            ],

            'position' => ['sometimes', 'integer', 'min:0'],
            'is_primary' => ['sometimes', 'boolean'],
            'contribution' => ['nullable', 'string', 'max:5000'],
        ]);

        DB::transaction(function () use ($content, $validated): void {
            $content->participants()->create([
                'user_id' => $validated['user_id'],
                'participant_role_id' => $validated['participant_role_id'],
                'position' => $validated['position'] ?? 0,
                'is_primary' => $validated['is_primary'] ?? false,
                'contribution' => $validated['contribution'] ?? null,
            ]);
        });

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('Participant added successfully.'),
        ]);

        return back();
    }

    /**
     * Update an existing participant assignment.
     */
    public function update(
        Request $request,
        string $type,
        int $contentId,
        int $participantId,
    ): RedirectResponse {
        $content = $this->resolveContent($type, $contentId);

        $this->authorize('update', $content);

        // Scope the assignment to this content item.
        $participant = $content->participants()
            ->whereKey($participantId)
            ->firstOrFail();

        $validated = $request->validate([
            'participant_role_id' => [
                'sometimes',
                'required',
                'integer',
                Rule::exists('participant_roles', 'id')
                    ->where('is_active', true),
            ],

            'position' => ['sometimes', 'integer', 'min:0'],
            'is_primary' => ['sometimes', 'boolean'],
            'contribution' => [
                'sometimes',
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        DB::transaction(function () use ($participant, $validated): void {
            $participant->update($validated);
        });

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('Participant updated successfully.'),
        ]);

        return back();
    }

    /**
     * Remove a participant assignment.
     */
    public function destroy(
        string $type,
        int $contentId,
        int $participantId,
    ): RedirectResponse {
        $content = $this->resolveContent($type, $contentId);

        // Prevent deleting an assignment belonging to another content item.
        $participant = $content->participants()
            ->whereKey($participantId)
            ->firstOrFail();

        DB::transaction(function () use ($participant): void {
            $participant->delete();
        });

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('Participant removed successfully.'),
        ]);

        return back();
    }

    /**
     * Resolve content through the centralized ModelResolver.
     */
    private function resolveContent(
        string $type,
        int $contentId,
    ): Model {
        abort_unless(
            ModelResolver::exists($type),
            404,
            'Unsupported content type.'
        );

        $content = ModelResolver::resolve($type, $contentId);

        // Only models with the participant relationship can use this module.
        abort_unless(
            method_exists($content, 'participants'),
            404,
            'Participant assignments are not supported for this content type.'
        );

        return $content;
    }
}
