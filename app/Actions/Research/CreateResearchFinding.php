<?php

namespace App\Actions\Research;

use App\Models\Research\Research;
use App\Models\Research\ResearchFinding;

class CreateResearchFinding
{
    public function handle(
        Research $research,
        array $data,
    ): ResearchFinding {
        return $research->findings()->create($data);
    }
}