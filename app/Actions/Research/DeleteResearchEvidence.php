<?php

namespace App\Actions\Research;

use App\Models\Research\ResearchEvidence;

class DeleteResearchEvidence
{
    public function handle(
        ResearchEvidence $evidence,
    ): void {
        $evidence->delete();
    }
}
