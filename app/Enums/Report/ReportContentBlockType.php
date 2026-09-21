<?php

namespace App\Enums\Report;

use App\Traits\Enums\HasDropdown;

enum ReportContentBlockType: string
{
    use HasDropdown;
    
    case Text = 'text';
    case Heading = 'heading';
    case Quote = 'quote';
    case Image = 'image';
    case Table = 'table';
    case Statistic = 'statistic';
    case Chart = 'chart';
    case Finding = 'finding';
    case Recommendation = 'recommendation';

    public function label(): string
    {
        return match ($this) {
            self::Text => 'Text',
            self::Heading => 'Heading',
            self::Quote => 'Quote',
            self::Image => 'Image',
            self::Table => 'Table',
            self::Statistic => 'Statistic',
            self::Chart => 'Chart',
            self::Finding => 'Finding',
            self::Recommendation => 'Recommendation',
        };
    }
}