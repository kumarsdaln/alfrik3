<?php
namespace App\Enums\Research;

use App\Traits\Enums\HasDropdown;

enum ResearchStatus: string
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
            self::Draft => 'secondary',
            self::Published => 'success',
            self::Archived => 'destructive',
        };
    }
}