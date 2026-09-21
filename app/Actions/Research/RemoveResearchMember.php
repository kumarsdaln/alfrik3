<?php

namespace App\Actions\Research;

use App\Models\Research\ResearchMember;

class RemoveResearchMember
{
    public function handle(
        ResearchMember $member,
    ): void {
        $member->delete();
    }
}