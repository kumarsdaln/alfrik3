<?php
namespace App\Enums\Research;

enum ResearchMemberRole: string
{
    case Owner = 'owner';
    case LeadResearcher = 'lead_researcher';
    case Researcher = 'researcher';
    case Contributor = 'contributor';
    case Reviewer = 'reviewer';
    case Editor = 'editor';

    public function label(): string
    {
        return match ($this) {
            self::Owner => 'Owner',
            self::LeadResearcher => 'Lead Researcher',
            self::Researcher => 'Researcher',
            self::Contributor => 'Contributor',
            self::Reviewer => 'Reviewer',
            self::Editor => 'Editor',
        };
    }
}