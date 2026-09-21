<?php

namespace App\Enums\SEO;

use App\Traits\Enums\HasDropdown;

enum SeoSchemaType: string
{
    use HasDropdown;
    
    case WebPage = 'WebPage';
    case Article = 'Article';
    case Report = 'Report';
    case ScholarlyArticle = 'ScholarlyArticle';
    case Person = 'Person';
    case Organization = 'Organization';
    case WebSite = 'WebSite';

    public function label(): string
    {
        return match ($this) {
            self::WebPage => 'Web Page',
            self::Article => 'Article',
            self::Report => 'Report',
            self::ScholarlyArticle => 'Scholarly Article',
            self::Person => 'Person',
            self::Organization => 'Organization',
            self::WebSite => 'Web Site',
        };
    }

    public static function default(): self
    {
        return self::WebPage;
    }
}