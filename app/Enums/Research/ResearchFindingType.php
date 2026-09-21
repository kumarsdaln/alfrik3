<?php
namespace App\Enums\Research;

use App\Traits\Enums\HasDropdown;

enum ResearchFindingType: string
{
    use HasDropdown;
    
    case Insight = 'insight';
    case Result = 'result';
    case Observation = 'observation';
    case Conclusion = 'conclusion';
    case Recommendation = 'recommendation';

    public function label(): string
    {
        return match ($this) {
            self::Insight => 'Insight',
            self::Result => 'Result',
            self::Observation => 'Observation',
            self::Conclusion => 'Conclusion',
            self::Recommendation => 'Recommendation',
        };
    }
}