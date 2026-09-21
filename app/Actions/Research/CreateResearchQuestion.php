<?php

namespace App\Actions\Research;

use App\Models\Research\Research;
use App\Models\Research\ResearchQuestion;

class CreateResearchQuestion
{
    public function handle(
        Research $research,
        array $data,
    ): ResearchQuestion {
        return $research->questions()->create($data);
    }
}
