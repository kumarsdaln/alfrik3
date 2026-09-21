<?php

namespace App\Actions\Research;

use App\Models\Research\ResearchSource;

class UpdateResearchSource
{
    public function handle(
        ResearchSource $source,
        array $data,
    ): ResearchSource {
        $source->update($data);

        return $source->fresh();
    }
}