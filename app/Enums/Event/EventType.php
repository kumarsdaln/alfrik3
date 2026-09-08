<?php

namespace App\Enums\Event;

use App\Traits\Enums\HasDropdown;

enum EventType: string
{
    use HasDropdown;
    
    case OFFLINE = 'offline';
    case ONLINE = 'online';
    case HYBRID = 'hybrid';

    public function label(): string
    {
        return match ($this) {
            self::OFFLINE => 'Offline',
            self::ONLINE => 'Online',
            self::HYBRID => 'Hybrid',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::OFFLINE => '#3B82F6',
            self::ONLINE => '#8B5CF6',
            self::HYBRID => '#F59E0B',
        };
    }
}