<?php

namespace App\Actions\Interview;

use App\Models\Interview\Interview;
use Illuminate\Support\Facades\DB;

class SyncInterviewTaxonomy
{
    public function handle(
        Interview $interview,
        array $categoryIds,
        array $tagIds,
    ): void {
        DB::transaction(function () use (
            $interview,
            $categoryIds,
            $tagIds
        ) {
            $interview->categories()->sync($categoryIds);

            $interview->tags()->sync($tagIds);
        });
    }
}