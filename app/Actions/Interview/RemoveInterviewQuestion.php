<?php

namespace App\Actions\Interview;

use App\Models\Interview\InterviewQuestion;
use Illuminate\Support\Facades\DB;

class RemoveInterviewQuestion
{
    public function handle(InterviewQuestion $question): void
    {
        DB::transaction(function () use ($question) {
            $question->delete();
        });
    }
}