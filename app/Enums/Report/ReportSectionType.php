<?php
namespace App\Enums\Report;

enum ReportSectionType: string
{
    case ExecutiveSummary = 'executive_summary';
    case Introduction = 'introduction';
    case Methodology = 'methodology';
    case LiteratureReview = 'literature_review';
    case Data = 'data';
    case Analysis = 'analysis';
    case Findings = 'findings';
    case Discussion = 'discussion';
    case Conclusion = 'conclusion';
    case Recommendations = 'recommendations';
    case References = 'references';
    case Appendix = 'appendix';
    case Content = 'content';

    public function label(): string
    {
        return match ($this) {
            self::ExecutiveSummary => 'Executive Summary',
            self::Introduction => 'Introduction',
            self::Methodology => 'Methodology',
            self::LiteratureReview => 'Literature Review',
            self::Data => 'Data',
            self::Analysis => 'Analysis',
            self::Findings => 'Findings',
            self::Discussion => 'Discussion',
            self::Conclusion => 'Conclusion',
            self::Recommendations => 'Recommendations',
            self::References => 'References',
            self::Appendix => 'Appendix',
            self::Content => 'Content',
        };
    }
}