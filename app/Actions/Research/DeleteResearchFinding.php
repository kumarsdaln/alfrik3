<?php

namespace App\Actions\Research;

use App\Models\Research\ResearchFinding;

class DeleteResearchFinding
{
    public function handle(ResearchFinding $finding): void
    {
        $finding->delete();
    }
}