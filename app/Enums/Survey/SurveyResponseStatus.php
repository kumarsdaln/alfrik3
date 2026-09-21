<?php

namespace App\Enums\Survey;

use App\Traits\Enums\HasDropdown;

enum SurveyResponseStatus: string
{
    use HasDropdown;
    
    case InProgress = 'in_progress';
    case Submitted = 'submitted';
    case Abandoned = 'abandoned';

    public function label(): string
    {
        return match ($this) {
            self::InProgress => 'In Progress',
            self::Submitted => 'Submitted',
            self::Abandoned => 'Abandoned',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::InProgress => '#D97706',
            self::Submitted => '#16A34A',
            self::Abandoned => '#64748B',
        };
    }
}