<?php

namespace App\Actions\Interview;

use App\Models\Interview\Interview;
use Illuminate\Support\Facades\DB;

class ReorderInterviewQuestions
{
    public function handle(Interview $interview, array $questions): void
    {
        DB::transaction(function () use ($interview, $questions) {
            foreach ($questions as $question) {
                $interview->questions()
                    ->whereKey($question['id'])
                    ->update([
                        'position' => $question['position'],
                    ]);
            }
        });
    }
}