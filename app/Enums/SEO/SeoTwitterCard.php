<?php

namespace App\Enums\SEO;

use App\Traits\Enums\HasDropdown;

enum SeoTwitterCard: string
{
    use HasDropdown;
    
    case Summary = 'summary';
    case SummaryLargeImage = 'summary_large_image';

    public function label(): string
    {
        return match ($this) {
            self::Summary => 'Summary',
            self::SummaryLargeImage => 'Summary with Large Image',
        };
    }

    public static function default(): self
    {
        return self::SummaryLargeImage;
    }
}