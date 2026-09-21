<?php

namespace App\Actions\Research;

use App\Models\Research\ResearchFinding;

class UpdateResearchFinding
{
    public function handle(
        ResearchFinding $finding,
        array $data,
    ): ResearchFinding {
        $finding->update($data);

        return $finding->fresh();
    }
}