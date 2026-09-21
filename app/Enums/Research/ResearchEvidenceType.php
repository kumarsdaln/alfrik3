<?php
namespace App\Enums\Research;

enum ResearchEvidenceType: string
{
    case Source = 'source';
    case Survey = 'survey';
    case SurveyQuestion = 'survey_question';
    case ResearchQuestion = 'research_question';
    case Finding = 'finding';
    case Observation = 'observation';
    case Dataset = 'dataset';

    public function label(): string
    {
        return match ($this) {
            self::Source => 'Research Source',
            self::Survey => 'Survey',
            self::SurveyQuestion => 'Survey Question',
            self::ResearchQuestion => 'Research Question',
            self::Finding => 'Research Finding',
            self::Observation => 'Observation',
            self::Dataset => 'Dataset',
        };
    }
}