<?php

namespace App\Actions\Interview;

use App\Models\Interview\InterviewAnswer;
use Illuminate\Support\Facades\DB;

class RemoveInterviewAnswer
{
    public function handle(InterviewAnswer $answer): void
    {
        DB::transaction(function () use ($answer) {
            $answer->delete();
        });
    }
}