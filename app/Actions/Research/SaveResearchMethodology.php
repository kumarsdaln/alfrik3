<?php

namespace App\Actions\Research;

use App\Models\Research\Research;
use App\Models\Research\ResearchMethodology;

class SaveResearchMethodology
{
    public function handle(
        Research $research,
        array $data,
    ): ResearchMethodology {
        return $research->methodology()->updateOrCreate(
            [],
            $data
        );
    }
}
