<?php

namespace App\Enums\Event;

use App\Traits\Enums\HasDropdown;

enum EventStatus: string
{
    use HasDropdown;
    
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::PUBLISHED => 'Published',
            self::CANCELLED => 'Cancelled',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::DRAFT => '#6B7280',
            self::PUBLISHED => '#22C55E',
            self::CANCELLED => '#EF4444',
        };
    }
}