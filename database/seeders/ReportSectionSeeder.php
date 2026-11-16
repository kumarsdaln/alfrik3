<?php

namespace Database\Seeders;

use App\Models\Report\Report;
use Illuminate\Database\Seeder;

class ReportSectionSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            'future-of-artificial-intelligence' => [
                [
                    'title' => 'Executive Summary',
                    'subtitle' => 'Key takeaways from the report',
                    'position' => 1,
                    'content' => 'Artificial intelligence is becoming an important part of modern business operations. Organisations are exploring its use in automation, analytics, customer service and product development. Successful adoption requires clear objectives, appropriate data governance and human oversight.',
                ],
                [
                    'title' => 'Introduction',
                    'subtitle' => 'Understanding artificial intelligence',
                    'position' => 2,
                    'content' => 'Artificial intelligence refers to technologies that perform tasks commonly associated with human intelligence. These include language processing, pattern recognition, prediction and content generation.',
                ],
                [
                    'title' => 'Methodology',
                    'subtitle' => 'Research approach and limitations',
                    'position' => 3,
                    'content' => 'This demonstration section describes a research framework based on secondary research, industry publications, technology documentation and comparative analysis. No primary survey data is included in this sample.',
                ],
                [
                    'title' => 'Industry Analysis',
                    'subtitle' => 'Applications across business functions',
                    'position' => 4,
                    'content' => 'Potential applications include customer support automation, software development assistance, document processing, forecasting, knowledge management and decision support.',
                ],
                [
                    'title' => 'Key Findings',
                    'subtitle' => 'What organisations should consider',
                    'position' => 5,
                    'content' => 'Adoption should be evaluated against measurable business outcomes. Data quality, security, regulatory requirements, workforce readiness and ongoing evaluation are important implementation considerations.',
                ],
                [
                    'title' => 'Recommendations',
                    'subtitle' => 'A practical adoption framework',
                    'position' => 6,
                    'content' => 'Start with a clearly defined business problem, run a limited pilot, establish human review and security controls, measure results and expand only when the evidence supports doing so.',
                ],
                [
                    'title' => 'Conclusion',
                    'subtitle' => 'Building sustainable AI capabilities',
                    'position' => 7,
                    'content' => 'Artificial intelligence can support meaningful business improvements when deployed responsibly. Organisations should treat adoption as an ongoing operational capability rather than a one-time technology purchase.',
                ],
            ],

            'global-software-industry-outlook' => [
                [
                    'title' => 'Executive Summary',
                    'subtitle' => 'Software industry overview',
                    'position' => 1,
                    'content' => 'The software industry continues to evolve through cloud services, platform business models, automation and specialised enterprise applications. This sample report provides a framework for examining these changes.',
                ],
                [
                    'title' => 'Industry Overview',
                    'subtitle' => 'The changing software landscape',
                    'position' => 2,
                    'content' => 'Software businesses operate across enterprise applications, infrastructure, developer tools, cybersecurity, data platforms and consumer products.',
                ],
                [
                    'title' => 'Business Models',
                    'subtitle' => 'How software companies generate revenue',
                    'position' => 3,
                    'content' => 'Common models include subscriptions, usage-based pricing, perpetual licensing, transaction fees, professional services and hybrid commercial arrangements.',
                ],
                [
                    'title' => 'Technology Trends',
                    'subtitle' => 'Technology shaping the industry',
                    'position' => 4,
                    'content' => 'Cloud-native architecture, artificial intelligence, cybersecurity, data engineering and platform integration are important areas to examine when assessing industry transformation.',
                ],
                [
                    'title' => 'Opportunities and Challenges',
                    'subtitle' => 'Strategic considerations',
                    'position' => 5,
                    'content' => 'Businesses may find opportunities through vertical specialisation, improved customer experience and automation. Challenges include competition, customer acquisition costs, security and product differentiation.',
                ],
                [
                    'title' => 'Conclusion',
                    'subtitle' => 'The path forward',
                    'position' => 6,
                    'content' => 'Software companies need a clear value proposition, sustainable distribution strategy and disciplined product development to compete over the long term.',
                ],
            ],

            'consumer-digital-behaviour-report' => [
                [
                    'title' => 'Introduction',
                    'subtitle' => 'Understanding digital consumers',
                    'position' => 1,
                    'content' => 'Digital consumer behaviour includes how people discover products, compare alternatives, evaluate trust and complete purchases across digital channels.',
                ],
                [
                    'title' => 'Research Framework',
                    'subtitle' => 'Dimensions of consumer behaviour',
                    'position' => 2,
                    'content' => 'A consumer research study can examine discovery channels, purchasing motivations, price sensitivity, customer experience, trust and post-purchase engagement.',
                ],
                [
                    'title' => 'Consumer Insights',
                    'subtitle' => 'Areas for further investigation',
                    'position' => 3,
                    'content' => 'This demonstration section identifies areas that a real consumer study should investigate. It does not represent findings from an actual survey.',
                ],
                [
                    'title' => 'Recommendations',
                    'subtitle' => 'Improving digital experiences',
                    'position' => 4,
                    'content' => 'Organisations should simplify customer journeys, communicate pricing clearly, provide reliable product information and establish accessible customer support.',
                ],
            ],
        ];

        foreach ($sections as $reportSlug => $reportSections) {
            $report = Report::query()
                ->where('slug', $reportSlug)
                ->first();

            if (! $report) {
                continue;
            }

            foreach ($reportSections as $section) {
                $report->sections()->updateOrCreate(
                    ['position' => $section['position']],
                    $section,
                );
            }
        }
    }
}
