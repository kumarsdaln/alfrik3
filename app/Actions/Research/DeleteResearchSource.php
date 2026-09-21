<?php

namespace App\Actions\Research;

use App\Models\Research\ResearchSource;

class DeleteResearchSource
{
    public function handle(ResearchSource $source): void
    {
        $source->delete();
    }
}