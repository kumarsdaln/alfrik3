<?php

namespace App\Actions\Interview;

use App\Models\Interview\InterviewAnswer;

class UpdateInterviewAnswer
{
    public function handle(
        InterviewAnswer $answer,
        array $data
    ): InterviewAnswer {
        $answer->update($data);

        return $answer->refresh();
    }
}
