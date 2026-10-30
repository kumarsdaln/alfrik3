<?php

namespace App\Enums\Event;

use App\Traits\Enums\HasDropdown;

enum EventType: string
{
    use HasDropdown;

    case ONLINE = 'online';
    case OFFLINE = 'offline';
    case HYBRID = 'hybrid';

    public function label(): string
    {
        return match ($this) {
            self::ONLINE => 'Online',
            self::OFFLINE => 'Offline',
            self::HYBRID => 'Hybrid',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::ONLINE => '#2563EB',
            self::OFFLINE => '#16A34A',
            self::HYBRID => '#9333EA',
        };
    }
}
