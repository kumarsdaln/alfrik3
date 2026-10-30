<?php

namespace App\Enums\User;

use App\Traits\Enums\HasDropdown;

enum UserStatus: int
{
    use HasDropdown;
    
    case Inactive = 0;
    case Active = 1;

    public function label(): string
    {
        return match ($this) {
            self::Inactive => 'Inactive',
            self::Active => 'Active',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Inactive => '#64748B',
            self::Active => '#16A34A',
        };
    }
}