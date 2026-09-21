<?php

namespace App\Actions\Research;

use App\Models\Research\ResearchInvitation;

class CancelResearchInvitation
{
    public function handle(
        ResearchInvitation $invitation,
    ): void {
        $invitation->delete();
    }
}
