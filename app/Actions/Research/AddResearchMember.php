<?php

namespace App\Actions\Research;

use App\Enums\Research\ResearchMemberRole;
use App\Models\Research\Research;
use App\Models\Research\ResearchMember;

class AddResearchMember
{
    public function handle(
        Research $research,
        int $userId,
        ResearchMemberRole|string $role,
    ): ResearchMember {
        $role = $role instanceof ResearchMemberRole
            ? $role
            : ResearchMemberRole::from($role);

        return ResearchMember::create([
            'research_id' => $research->id,
            'user_id' => $userId,
            'role' => $role,
            'joined_at' => now(),
        ]);
    }
}