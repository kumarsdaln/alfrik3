<?php

namespace App\Http\Controllers\Admin\Research;

use App\Actions\Research\CancelResearchInvitation;
use App\Actions\Research\CreateResearchInvitation;
use App\Enums\Research\ResearchMemberRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Research\StoreResearchInvitationRequest;
use App\Http\Resources\Research\ResearchInvitationResource;
use App\Models\Research\Research;
use App\Models\Research\ResearchInvitation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ResearchInvitationController extends Controller
{
    public function index(
        Research $research,
    ): Response {
        $invitations = $research->invitations()
            ->latest()
            ->get();

        return Inertia::render(
            'admin/research/invitations/Index',
            [
                'research' => [
                    'id' => $research->id,
                    'title' => $research->title,
                ],

                'invitations' =>
                ResearchInvitationResource::collection(
                    $invitations
                ),

                'roleOptions' => collect(
                    ResearchMemberRole::cases()
                )
                    ->map(fn(ResearchMemberRole $role) => [
                        'value' => $role->value,
                        'label' => $role->label(),
                    ])
                    ->values()
                    ->all(),
            ]
        );
    }

    public function store(
        StoreResearchInvitationRequest $request,
        Research $research,
        CreateResearchInvitation $action,
    ): RedirectResponse {
        $data = $request->validated();

        $alreadyMember = $research->members()
            ->whereHas('user', function ($query) use ($data) {
                $query->where('email', $data['email']);
            })
            ->exists();

        if ($alreadyMember) {
            return back()->withErrors([
                'email' => 'This user is already a member of this research.',
            ]);
        }

        $pendingInvitation = $research->invitations()
            ->where('email', $data['email'])
            ->whereNull('accepted_at')
            ->where('expires_at', '>', now())
            ->exists();

        if ($pendingInvitation) {
            return back()->withErrors([
                'email' => 'A pending invitation already exists for this email.',
            ]);
        }

        $action->handle(
            research: $research,
            invitedBy: $request->user(),
            email: $data['email'],
            role: $data['role'],
            expiresAt: $data['expires_at'] ?? null,
        );

        return back()->with(
            'success',
            'Research invitation created successfully.'
        );
    }

    public function destroy(
        Research $research,
        ResearchInvitation $invitation,
        CancelResearchInvitation $action,
    ): RedirectResponse {
        abort_unless(
            $invitation->research_id === $research->id,
            404
        );

        $action->handle($invitation);

        return back()->with(
            'success',
            'Research invitation cancelled successfully.'
        );
    }
}
