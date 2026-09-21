<?php

namespace App\Actions\Research;

use App\Models\Research\ResearchEvidence;

class UpdateResearchEvidence
{
    public function handle(
        ResearchEvidence $evidence,
        array $data,
    ): ResearchEvidence {
        $evidence->update($data);

        return $evidence->fresh();
    }
}