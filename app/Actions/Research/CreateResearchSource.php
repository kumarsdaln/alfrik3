<?php

namespace App\Actions\Research;

use App\Models\Research\Research;
use App\Models\Research\ResearchSource;

class CreateResearchSource
{
    public function handle(
        Research $research,
        array $data,
    ): ResearchSource {
        return $research->sources()->create($data);
    }
}