<?php

namespace App\Http\Controllers\Public\Research;

use App\Actions\Research\AcceptResearchInvitation;
use App\Http\Controllers\Controller;
use App\Models\Research\ResearchInvitation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ResearchInvitationController extends Controller
{
    public function show(
        string $token,
    ): Response|RedirectResponse {
        $invitation = ResearchInvitation::query()
            ->with('research')
            ->where('token', $token)
            ->firstOrFail();

        if ($invitation->accepted_at !== null) {
            return redirect()
                ->route(
                    'research.invitation.accepted',
                    ['token' => $token]
                );
        }

        if ($invitation->expires_at->isPast()) {
            return Inertia::render(
                'research/invitations/Expired',
                [
                    'research' => [
                        'id' => $invitation->research->id,
                        'title' => $invitation->research->title,
                    ],
                ]
            );
        }

        return Inertia::render(
            'research/invitations/Show',
            [
                'invitation' => [
                    'email' => $invitation->email,
                    'role' => [
                        'value' => $invitation->role->value,
                        'label' => $invitation->role->label(),
                    ],
                    'expires_at' => $invitation->expires_at
                        ->toISOString(),
                ],

                'research' => [
                    'id' => $invitation->research->id,
                    'title' => $invitation->research->title,
                ],
            ]
        );
    }

    public function accept(
        Request $request,
        string $token,
        AcceptResearchInvitation $action,
    ): RedirectResponse {
        $user = $request->user();

        if (! $user) {
            return redirect()
                ->route('login')
                ->with(
                    'invitation_token',
                    $token
                );
        }

        $invitation = ResearchInvitation::query()
            ->with('research')
            ->where('token', $token)
            ->firstOrFail();

        $action->handle(
            $invitation,
            $user
        );

        return redirect()->route(
            'admin.research.show',
            [
                'research' => $invitation->research,
            ]
        )->with(
            'success',
            'You have joined the research team successfully.'
        );
    }

    public function accepted(
        string $token,
    ): Response {
        $invitation = ResearchInvitation::query()
            ->with('research')
            ->where('token', $token)
            ->firstOrFail();

        return Inertia::render(
            'research/invitations/Accepted',
            [
                'research' => [
                    'id' => $invitation->research->id,
                    'title' => $invitation->research->title,
                ],
            ]
        );
    }
}