<?php

namespace App\Actions\Interview;

use App\Models\Interview\Interview;
use App\Models\Interview\InterviewParticipant;

class AddInterviewParticipant
{
    public function handle(
        Interview $interview,
        array $data
    ): InterviewParticipant {
        return $interview->participants()->create($data);
    }
}