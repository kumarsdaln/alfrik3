<?php

namespace App\Enums\Research;

use App\Traits\Enums\HasDropdown;

enum ResearchSourceType: string
{
    use HasDropdown;
    
    case Government = 'government';
    case Academic = 'academic';
    case Industry = 'industry';
    case Organization = 'organization';
    case Survey = 'survey';
    case Interview = 'interview';
    case Dataset = 'dataset';
    case Website = 'website';
    case Report = 'report';
    case Book = 'book';
    case Article = 'article';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Government => 'Government',
            self::Academic => 'Academic',
            self::Industry => 'Industry',
            self::Organization => 'Organization',
            self::Survey => 'Survey',
            self::Interview => 'Interview',
            self::Dataset => 'Dataset',
            self::Website => 'Website',
            self::Report => 'Report',
            self::Book => 'Book',
            self::Article => 'Article',
            self::Other => 'Other',
        };
    }
}
