<?php

namespace App\Enums\SEO;

use App\Traits\Enums\HasDropdown;

enum SeoOgType: string
{
    use HasDropdown;
    
    case Website = 'website';
    case Article = 'article';

    public function label(): string
    {
        return match ($this) {
            self::Website => 'Website',
            self::Article => 'Article',
        };
    }

    public static function default(): self
    {
        return self::Website;
    }
}