<?php

namespace App\Actions\Research;

use App\Models\Research\ResearchQuestion;

class UpdateResearchQuestion
{
    public function handle(
        ResearchQuestion $question,
        array $data,
    ): ResearchQuestion 
    {
        $question->update($data);

        return $question->fresh();
    }
}