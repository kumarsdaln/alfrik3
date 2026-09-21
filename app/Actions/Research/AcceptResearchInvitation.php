<?php

namespace App\Actions\Research;

use App\Models\Research\ResearchInvitation;
use App\Models\Research\ResearchMember;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AcceptResearchInvitation
{
    public function handle(
        ResearchInvitation $invitation,
        User $user,
    ): ResearchMember {
        return DB::transaction(function () use (
            $invitation,
            $user,
        ) {
            abort_if(
                $invitation->accepted_at !== null,
                422,
                'This invitation has already been accepted.'
            );

            abort_if(
                $invitation->expires_at->isPast(),
                422,
                'This invitation has expired.'
            );

            abort_if(
                $invitation->email !== $user->email,
                403,
                'This invitation was not issued for your account.'
            );

            $member = ResearchMember::firstOrCreate(
                [
                    'research_id' => $invitation->research_id,
                    'user_id' => $user->id,
                ],
                [
                    'role' => $invitation->role,
                    'joined_at' => now(),
                ]
            );

            $invitation->update([
                'user_id' => $user->id,
                'accepted_at' => now(),
            ]);

            return $member;
        });
    }
}
