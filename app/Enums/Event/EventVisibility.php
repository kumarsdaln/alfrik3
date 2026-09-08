<?php

namespace App\Enums\Event;

use App\Traits\Enums\HasDropdown;

enum EventVisibility: string
{
    use HasDropdown; 
    
    case PUBLIC = 'public';
    case PRIVATE = 'private';

    public function label(): string
    {
        return match ($this) {
            self::PUBLIC => 'Public',
            self::PRIVATE => 'Private',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PUBLIC => '#22C55E',
            self::PRIVATE => '#6B7280',
        };
    }
}