<?php

namespace App\Enums\Magazine;

use App\Traits\Enums\HasDropdown;

enum MagazineArticleType: string
{
    use HasDropdown;
    
    case Article = 'article';
    case Interview = 'interview';
    case Opinion = 'opinion';
    case Feature = 'feature';
    case News = 'news';

    public function label(): string
    {
        return match ($this) {
            self::Article => 'Article',
            self::Interview => 'Interview',
            self::Opinion => 'Opinion',
            self::Feature => 'Feature',
            self::News => 'News',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Article => '#3B82F6',
            self::Interview => '#8B5CF6',
            self::Opinion => '#F59E0B',
            self::Feature => '#10B981',
            self::News => '#EF4444',
        };
    }
}
