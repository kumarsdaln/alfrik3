<?php

namespace App\Actions\Research;

use App\Enums\Research\ResearchMemberRole;
use App\Models\Research\ResearchMember;

class UpdateResearchMember
{
    public function handle(
        ResearchMember $member,
        ResearchMemberRole|string $role,
    ): ResearchMember {
        $role = $role instanceof ResearchMemberRole
            ? $role
            : ResearchMemberRole::from($role);

        $member->update([
            'role' => $role,
        ]);

        return $member->fresh();
    }
}
