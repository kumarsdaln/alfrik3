<?php

namespace App\Enums\Interview;

use App\Traits\Enums\HasDropdown;

enum InterviewParticipantRole: string
{
    use HasDropdown;

    case Interviewer = 'interviewer';
    case Interviewee = 'interviewee';

    public function label(): string
    {
        return match ($this) {
            self::Interviewer => 'Interviewer',
            self::Interviewee => 'Interviewee',
        };
    }
}
