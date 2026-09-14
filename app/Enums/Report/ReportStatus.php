<?php
namespace App\Enums\Report;

use App\Traits\Enums\HasDropdown;

enum ReportStatus: boolean
{
    use HasDropdown;
    
    case DRAFT = false;
    case PUBLISHED = true;

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