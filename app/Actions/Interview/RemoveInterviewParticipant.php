<?php

namespace App\Actions\Interview;

use App\Models\Interview\InterviewParticipant;
use Illuminate\Support\Facades\DB;

class RemoveInterviewParticipant
{
    public function handle(InterviewParticipant $participant): void
    {
        DB::transaction(function () use ($participant) {
            $participant->delete();
        });
    }
}