<?php
namespace App\Enums\Report;

enum ReportVersionStatus: string
{
    case Draft = 'draft';
    case Review = 'review';
    case Published = 'published';
    case Superseded = 'superseded';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Review => 'Under Review',
            self::Published => 'Published',
            self::Superseded => 'Superseded',
        };
    }
}