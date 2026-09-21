<?php

namespace App\Actions\Research;

use App\Enums\Research\ResearchMemberRole;
use App\Models\Research\Research;
use App\Models\Research\ResearchInvitation;
use App\Models\User;
use Illuminate\Support\Str;

class CreateResearchInvitation
{
    public function handle(
        Research $research,
        User $invitedBy,
        string $email,
        ResearchMemberRole|string $role,
        ?string $expiresAt = null,
    ): ResearchInvitation {
        $role = $role instanceof ResearchMemberRole
            ? $role
            : ResearchMemberRole::from($role);

        $user = User::query()
            ->where('email', $email)
            ->first();

        return ResearchInvitation::create([
            'research_id' => $research->id,
            'invited_by' => $invitedBy->id,
            'user_id' => $user?->id,
            'email' => $email,
            'role' => $role,
            'token' => Str::random(64),
            'expires_at' => $expiresAt
                ? now()->parse($expiresAt)
                : now()->addDays(7),
        ]);
    }
}
