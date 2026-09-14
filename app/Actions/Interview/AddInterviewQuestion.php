<?php

namespace App\Actions\Interview;

use App\Models\Interview\Interview;
use App\Models\Interview\InterviewQuestion;
use Illuminate\Support\Facades\DB;

class AddInterviewQuestion
{
    public function handle(
        Interview $interview,
        array $data,
    ): InterviewQuestion {
        return DB::transaction(function () use ($interview, $data) {
            return $interview->questions()->create($data);
        });
    }
}