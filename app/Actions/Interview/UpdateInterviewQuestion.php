<?php

namespace App\Actions\Interview;

use App\Models\Interview\InterviewQuestion;
use Illuminate\Support\Facades\DB;

class UpdateInterviewQuestion
{
    public function handle(
        InterviewQuestion $question,
        array $data,
    ): InterviewQuestion {
        return DB::transaction(function () use ($question, $data) {
            $question->update($data);

            return $question->refresh();
        });
    }
}