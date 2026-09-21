<?php

namespace App\Enums\SEO;

use App\Traits\Enums\HasDropdown;

enum SeoLocale: string
{
    use HasDropdown; 
    
    case EnglishIndia = 'en-IN';
    case EnglishUnitedStates = 'en-US';
    case EnglishUnitedKingdom = 'en-GB';

    public function label(): string
    {
        return match ($this) {
            self::EnglishIndia => 'English (India)',
            self::EnglishUnitedStates => 'English (United States)',
            self::EnglishUnitedKingdom => 'English (United Kingdom)',
        };
    }

    public function language(): string
    {
        return 'English';
    }

    public function country(): string
    {
        return match ($this) {
            self::EnglishIndia => 'India',
            self::EnglishUnitedStates => 'United States',
            self::EnglishUnitedKingdom => 'United Kingdom',
        };
    }

    public function flag(): string
    {
        return match ($this) {
            self::EnglishIndia => '🇮🇳',
            self::EnglishUnitedStates => '🇺🇸',
            self::EnglishUnitedKingdom => '🇬🇧',
        };
    }

    public function direction(): string
    {
        return 'ltr';
    }

    public function htmlLang(): string
    {
        return $this->value;
    }

    public static function default(): self
    {
        return self::EnglishIndia;
    }
}