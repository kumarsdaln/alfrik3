<?php

namespace App\Actions\Research;

use App\Models\Research\ResearchQuestion;

class DeleteResearchQuestion
{
    public function handle(ResearchQuestion $question): void
    {
        $question->delete();
    }
}