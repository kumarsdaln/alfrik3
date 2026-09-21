<?php

namespace App\Enums\Report;

use App\Traits\Enums\HasDropdown;

enum ReportType: string
{
    use HasDropdown;
    
    case Research = 'research';
    case Industry = 'industry';
    case Market = 'market';
    case Consumer = 'consumer';
    case Trend = 'trend';
    case Analysis = 'analysis';
    case Whitepaper = 'whitepaper';
    case Annual = 'annual';
    case Survey = 'survey';
    case General = 'general';

    public function label(): string
    {
        return match ($this) {
            self::Research => 'Research Report',
            self::Industry => 'Industry Report',
            self::Market => 'Market Report',
            self::Consumer => 'Consumer Report',
            self::Trend => 'Trend Report',
            self::Analysis => 'Analysis Report',
            self::Whitepaper => 'Whitepaper',
            self::Annual => 'Annual Report',
            self::Survey => 'Survey Report',
            self::General => 'General Report',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Research => '#2563EB',
            self::Industry => '#0891B2',
            self::Market => '#16A34A',
            self::Consumer => '#7C3AED',
            self::Trend => '#D97706',
            self::Analysis => '#0F766E',
            self::Whitepaper => '#475569',
            self::Annual => '#9333EA',
            self::Survey => '#DB2777',
            self::General => '#64748B',
        };
    }
}