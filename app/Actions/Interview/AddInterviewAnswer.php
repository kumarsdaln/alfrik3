<?php

namespace App\Actions\Interview;

use App\Models\Interview\InterviewAnswer;
use App\Models\Interview\InterviewQuestion;

class AddInterviewAnswer
{
    public function handle(
        InterviewQuestion $question,
        array $data
    ): InterviewAnswer {
        return $question->answers()->create($data);
    }
}