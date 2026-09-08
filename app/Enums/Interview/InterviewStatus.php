<?php

namespace App\Enums\Interview;

use App\Traits\Enums\HasDropdown;

enum InterviewStatus: string
{
    use HasDropdown;
    
    case DRAFT = 'draft';
    case PUBLISHED = 'published';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::PUBLISHED => 'Published',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::DRAFT => '#6B7280',
            self::PUBLISHED => '#22C55E',
        };
    }
}