<?php

namespace App\Enums\Research;

use App\Traits\Enums\HasDropdown;

enum ResearchQuestionType: string
{
    use HasDropdown;
    
    case Primary = 'primary';
    case Secondary = 'secondary';
    case Hypothesis = 'hypothesis';

    public function label(): string
    {
        return match ($this) {
            self::Primary => 'Primary Question',
            self::Secondary => 'Secondary Question',
            self::Hypothesis => 'Hypothesis',
        };
    }
}