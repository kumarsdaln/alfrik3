<?php

namespace App\Enums\Magazine;

use App\Traits\Enums\HasDropdown;

enum MagazineArticleStatus: string
{
    use HasDropdown;
    
    case Draft = 'draft';
    case Published = 'published';
    case Archived = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Published => 'Published',
            self::Archived => 'Archived',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Draft => '#6B7280',
            self::Published => '#22C55E',
            self::Archived => '#F59E0B',
        };
    }
}