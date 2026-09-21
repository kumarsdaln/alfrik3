<?php

namespace App\Actions\Research;

use App\Models\Research\ResearchFinding;

class SyncResearchFindingQuestions
{
    public function handle(
        ResearchFinding $finding,
        array $questionIds,
    ): void {
        $syncData = [];

        foreach (array_values($questionIds) as $position => $questionId) {
            $syncData[$questionId] = [
                'position' => $position,
            ];
        }

        $finding->questions()->sync($syncData);
    }
}
