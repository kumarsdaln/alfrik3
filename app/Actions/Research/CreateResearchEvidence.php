<?php

namespace App\Actions\Research;

use App\Models\Research\ResearchEvidence;
use App\Models\Research\ResearchFinding;

class CreateResearchEvidence
{
    public function handle(
        ResearchFinding $finding,
        array $data,
    ): ResearchEvidence {
        $data['research_id'] = $finding->research_id;
        $data['finding_id'] = $finding->id;

        return ResearchEvidence::create($data);
    }
}