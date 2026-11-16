<?php

namespace Database\Seeders;

use App\Enums\Report\ReportContentBlockType;
use App\Models\Report\Report;
use App\Models\Report\ReportSection;
use Illuminate\Database\Seeder;

class ReportContentBlockSeeder extends Seeder
{
    public function run(): void
    {
        $blocks = [
            'future-of-artificial-intelligence' => [
                'Executive Summary' => [
                    [
                        'type' => ReportContentBlockType::Heading,
                        'title' => 'Report Overview',
                        'description' => 'An introduction to the central themes of this report.',
                        'content' => [
                            'text' => 'Artificial Intelligence and Business Transformation',
                            'level' => 2,
                        ],
                        'position' => 1,
                    ],
                    [
                        'type' => ReportContentBlockType::Text,
                        'title' => 'Overview',
                        'description' => 'Summary of the report.',
                        'content' => [
                            'text' => 'Artificial intelligence is creating opportunities to improve business processes, support employees and develop new products. Its successful implementation depends on data quality, governance, security and measurable outcomes.',
                        ],
                        'position' => 2,
                    ],
                    [
                        'type' => ReportContentBlockType::Statistic,
                        'title' => 'Illustrative adoption indicator',
                        'description' => 'Demonstration statistic only; not a real research finding.',
                        'content' => [
                            'value' => '75%',
                            'label' => 'Illustrative adoption rate',
                            'context' => 'Placeholder value for demonstrating a statistic block.',
                        ],
                        'position' => 3,
                    ],
                    [
                        'type' => ReportContentBlockType::Quote,
                        'title' => 'Key Perspective',
                        'description' => 'A sample editorial quotation.',
                        'content' => [
                            'text' => 'Technology creates value when it solves a clearly defined problem.',
                            'attribution' => 'Editorial example',
                        ],
                        'position' => 4,
                    ],
                ],

                'Industry Analysis' => [
                    [
                        'type' => ReportContentBlockType::Heading,
                        'title' => 'Business Applications',
                        'description' => 'Areas where AI can support organisations.',
                        'content' => [
                            'text' => 'Where AI Creates Business Value',
                            'level' => 2,
                        ],
                        'position' => 1,
                    ],
                    [
                        'type' => ReportContentBlockType::Table,
                        'title' => 'AI Application Areas',
                        'description' => 'Illustrative classification of AI use cases.',
                        'content' => [
                            'headers' => [
                                'Business Function',
                                'Example Application',
                                'Potential Benefit',
                            ],
                            'rows' => [
                                [
                                    'Customer Support',
                                    'AI-assisted responses',
                                    'Faster response handling',
                                ],
                                [
                                    'Software Development',
                                    'Code assistance',
                                    'Developer productivity',
                                ],
                                [
                                    'Finance',
                                    'Document processing',
                                    'Process automation',
                                ],
                                [
                                    'Human Resources',
                                    'Knowledge assistance',
                                    'Improved information access',
                                ],
                            ],
                        ],
                        'position' => 2,
                    ],
                    [
                        'type' => ReportContentBlockType::Chart,
                        'title' => 'Illustrative AI Adoption Distribution',
                        'description' => 'Sample chart data for testing the chart block.',
                        'content' => [
                            'chart_type' => 'bar',
                            'labels' => [
                                'Customer Support',
                                'Software',
                                'Finance',
                                'Marketing',
                            ],
                            'datasets' => [
                                [
                                    'label' => 'Illustrative adoption index',
                                    'data' => [80, 70, 55, 65],
                                ],
                            ],
                        ],
                        'position' => 3,
                    ],
                ],

                'Key Findings' => [
                    [
                        'type' => ReportContentBlockType::Finding,
                        'title' => 'Governance is essential',
                        'description' => 'A key consideration for AI implementation.',
                        'content' => [
                            'finding' => 'AI adoption requires clear governance, accountability and human oversight.',
                            'implication' => 'Organisations should define ownership, review processes and acceptable-use policies before scaling deployments.',
                        ],
                        'position' => 1,
                    ],
                    [
                        'type' => ReportContentBlockType::Finding,
                        'title' => 'Data quality affects outcomes',
                        'description' => 'A key consideration for AI implementation.',
                        'content' => [
                            'finding' => 'The quality and suitability of input data influence the reliability of AI outputs.',
                            'implication' => 'Data preparation and validation should be part of the implementation plan.',
                        ],
                        'position' => 2,
                    ],
                ],

                'Recommendations' => [
                    [
                        'type' => ReportContentBlockType::Recommendation,
                        'title' => 'Start with focused use cases',
                        'description' => 'A practical implementation recommendation.',
                        'content' => [
                            'recommendation' => 'Select a specific business problem with measurable outcomes.',
                            'actions' => [
                                'Identify the current process and its limitations.',
                                'Define measurable success criteria.',
                                'Run a controlled pilot.',
                                'Evaluate results before expanding.',
                            ],
                            'priority' => 'high',
                        ],
                        'position' => 1,
                    ],
                    [
                        'type' => ReportContentBlockType::Recommendation,
                        'title' => 'Establish responsible AI controls',
                        'description' => 'A governance recommendation.',
                        'content' => [
                            'recommendation' => 'Introduce appropriate security, privacy and human-review controls.',
                            'actions' => [
                                'Define access permissions.',
                                'Review sensitive data handling.',
                                'Establish human approval for high-impact decisions.',
                                'Monitor system performance.',
                            ],
                            'priority' => 'high',
                        ],
                        'position' => 2,
                    ],
                ],
            ],

            'global-software-industry-outlook' => [
                'Business Models' => [
                    [
                        'type' => ReportContentBlockType::Table,
                        'title' => 'Software Revenue Models',
                        'description' => 'Common software monetisation approaches.',
                        'content' => [
                            'headers' => [
                                'Model',
                                'Description',
                                'Example',
                            ],
                            'rows' => [
                                [
                                    'Subscription',
                                    'Recurring payment for access',
                                    'Monthly SaaS plan',
                                ],
                                [
                                    'Usage-based',
                                    'Payment based on consumption',
                                    'API usage billing',
                                ],
                                [
                                    'Licensing',
                                    'Payment for software rights',
                                    'Enterprise software licence',
                                ],
                                [
                                    'Professional Services',
                                    'Revenue from implementation and support',
                                    'Custom integration',
                                ],
                            ],
                        ],
                        'position' => 1,
                    ],
                ],

                'Technology Trends' => [
                    [
                        'type' => ReportContentBlockType::Statistic,
                        'title' => 'Illustrative technology priorities',
                        'description' => 'Placeholder content for demonstrating statistics.',
                        'content' => [
                            'value' => '5',
                            'label' => 'Technology areas',
                            'context' => 'Cloud, AI, cybersecurity, data engineering and platform integration.',
                        ],
                        'position' => 1,
                    ],
                    [
                        'type' => ReportContentBlockType::Text,
                        'title' => 'Technology Overview',
                        'description' => 'Technology areas to evaluate.',
                        'content' => [
                            'text' => 'A detailed industry assessment should evaluate technology adoption, infrastructure costs, integration complexity, security requirements and customer demand.',
                        ],
                        'position' => 2,
                    ],
                ],

                'Opportunities and Challenges' => [
                    [
                        'type' => ReportContentBlockType::Finding,
                        'title' => 'Product differentiation',
                        'description' => 'A strategic industry consideration.',
                        'content' => [
                            'finding' => 'Software companies need a differentiated value proposition in competitive markets.',
                            'implication' => 'Specialised products and strong customer understanding can help companies establish a defensible position.',
                        ],
                        'position' => 1,
                    ],
                ],
            ],

            'consumer-digital-behaviour-report' => [
                'Research Framework' => [
                    [
                        'type' => ReportContentBlockType::Table,
                        'title' => 'Consumer Research Dimensions',
                        'description' => 'Suggested dimensions for a consumer research study.',
                        'content' => [
                            'headers' => [
                                'Dimension',
                                'Research Question',
                            ],
                            'rows' => [
                                [
                                    'Discovery',
                                    'How do consumers discover products?',
                                ],
                                [
                                    'Trust',
                                    'What influences consumer trust?',
                                ],
                                [
                                    'Pricing',
                                    'How does price affect purchase decisions?',
                                ],
                                [
                                    'Experience',
                                    'What makes a digital experience convenient?',
                                ],
                                [
                                    'Retention',
                                    'What encourages repeat purchases?',
                                ],
                            ],
                        ],
                        'position' => 1,
                    ],
                ],

                'Consumer Insights' => [
                    [
                        'type' => ReportContentBlockType::Finding,
                        'title' => 'Trust and transparency',
                        'description' => 'A research hypothesis, not a verified finding.',
                        'content' => [
                            'finding' => 'Trust and transparent product information may influence digital purchase decisions.',
                            'implication' => 'A real study should test this hypothesis using primary consumer research.',
                        ],
                        'position' => 1,
                    ],
                ],

                'Recommendations' => [
                    [
                        'type' => ReportContentBlockType::Recommendation,
                        'title' => 'Improve the customer journey',
                        'description' => 'A practical digital experience recommendation.',
                        'content' => [
                            'recommendation' => 'Make product discovery, evaluation and purchasing straightforward.',
                            'actions' => [
                                'Simplify navigation.',
                                'Provide clear product details.',
                                'Make pricing transparent.',
                                'Improve customer support.',
                            ],
                            'priority' => 'medium',
                        ],
                        'position' => 1,
                    ],
                ],
            ],
        ];

        foreach ($blocks as $reportSlug => $sectionBlocks) {
            $report = Report::query()
                ->where('slug', $reportSlug)
                ->first();

            if (! $report) {
                continue;
            }

            foreach ($sectionBlocks as $sectionTitle => $contentBlocks) {
                $section = $report->sections()
                    ->where('title', $sectionTitle)
                    ->first();

                if (! $section) {
                    continue;
                }

                foreach ($contentBlocks as $block) {
                    $section->blocks()->updateOrCreate(
                        ['position' => $block['position']],
                        $block,
                    );
                }
            }
        }
    }
}