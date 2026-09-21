<?php

namespace App\Enums\Survey;

use App\Traits\Enums\HasDropdown;

enum SurveyStatus: string
{
    use HasDropdown;
    
    case Draft = 'draft';
    case Published = 'published';
    case Closed = 'closed';
    case Archived = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Published => 'Published',
            self::Closed => 'Closed',
            self::Archived => 'Archived',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Draft => 'secondary',
            self::Published => 'success',
            self::Closed => 'warning',
            self::Archived => 'destructive',
        };
    }
}
