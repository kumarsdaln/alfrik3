<?php

namespace App\Enums\Survey;

use App\Traits\Enums\HasDropdown;

enum SurveyQuestionType: string
{
    use HasDropdown;
    
    case ShortText = 'short_text';
    case LongText = 'long_text';
    case SingleChoice = 'single_choice';
    case MultipleChoice = 'multiple_choice';
    case YesNo = 'yes_no';
    case Number = 'number';
    case Rating = 'rating';
    case Scale = 'scale';
    case Date = 'date';

    public function label(): string
    {
        return match ($this) {
            self::ShortText => 'Short Text',
            self::LongText => 'Long Text',
            self::SingleChoice => 'Single Choice',
            self::MultipleChoice => 'Multiple Choice',
            self::YesNo => 'Yes / No',
            self::Number => 'Number',
            self::Rating => 'Rating',
            self::Scale => 'Scale',
            self::Date => 'Date',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::ShortText => '#2563EB',
            self::LongText => '#0891B2',
            self::SingleChoice => '#16A34A',
            self::MultipleChoice => '#7C3AED',
            self::YesNo => '#D97706',
            self::Number => '#0F766E',
            self::Rating => '#DB2777',
            self::Scale => '#475569',
            self::Date => '#64748B',
        };
    }
}