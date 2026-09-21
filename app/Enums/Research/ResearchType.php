<?php

namespace App\Enums\Research;

use App\Traits\Enums\HasDropdown;

enum ResearchType: string
{
    use HasDropdown;

    case Industry = 'industry';
    case Market = 'market';
    case Consumer = 'consumer';
    case Trend = 'trend';
    case Brand = 'brand';
    case Academic = 'academic';
    case DataAnalysis = 'data_analysis';
    case General = 'general';

    public function label(): string
    {
        return match ($this) {
            self::Industry => 'Industry Research',
            self::Market => 'Market Research',
            self::Consumer => 'Consumer Research',
            self::Trend => 'Trend Research',
            self::Brand => 'Brand Research',
            self::Academic => 'Academic Research',
            self::DataAnalysis => 'Data Analysis',
            self::General => 'General Research',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Industry => '#2563EB',
            self::Market => '#0891B2',
            self::Consumer => '#16A34A',
            self::Trend => '#D97706',
            self::Brand => '#7C3AED',
            self::Academic => '#475569',
            self::DataAnalysis => '#0F766E',
            self::General => '#64748B',
        };
    }
}
