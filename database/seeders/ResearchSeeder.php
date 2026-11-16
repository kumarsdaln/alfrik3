<?php

namespace Database\Seeders;

use App\Enums\Research\ResearchEvidenceType;
use App\Enums\Research\ResearchFindingType;
use App\Enums\Research\ResearchQuestionType;
use App\Enums\Research\ResearchSourceType;
use App\Enums\Research\ResearchStatus;
use App\Enums\Research\ResearchType;
use App\Models\Research\Research;
use App\Models\Research\ResearchEvidence;
use App\Models\Research\ResearchFinding;
use App\Models\Research\ResearchMethodology;
use App\Models\Research\ResearchQuestion;
use App\Models\Research\ResearchSource;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class ResearchSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            foreach ($this->researches() as $data) {
                $research = Research::updateOrCreate(
                    ['slug' => Str::slug($data['title'])],
                    [
                        'title' => $data['title'],
                        'subtitle' => $data['subtitle'],
                        'description' => $data['description'],
                        'summary' => $data['summary'],
                        'type' => $data['type'],
                        'status' => $data['status'],
                        'featured' => $data['featured'],
                        'published_at' => $data['published_at'],
                    ]
                );

                $this->seedMethodology(
                    $research,
                    $data['methodology']
                );

                $this->seedQuestions(
                    $research,
                    $data['questions']
                );

                $sources = $this->seedSources(
                    $research,
                    $data['sources']
                );

                $this->seedFindings(
                    $research,
                    $data['findings'],
                    $sources
                );
            }
        });
    }

    /**
     * Seed research methodology.
     */
    private function seedMethodology(
        Research $research,
        array $data
    ): void {
        ResearchMethodology::updateOrCreate(
            ['research_id' => $research->id],
            $data
        );
    }

    /**
     * Seed research questions.
     */
    private function seedQuestions(
        Research $research,
        array $questions
    ): void {
        foreach ($questions as $index => $question) {
            $research->questions()->updateOrCreate(
                ['position' => $index + 1],
                $question
            );
        }
    }

    /**
     * Seed research sources.
     *
     * The key is an internal identifier only.
     * It is never inserted into the database.
     */
    private function seedSources(
        Research $research,
        array $sources
    ): array {
        $sourceRecords = [];

        foreach ($sources as $index => $source) {
            $key = $source['key'];

            unset($source['key']);

            $record = $research->sources()->updateOrCreate(
                ['position' => $index + 1],
                $source
            );

            $sourceRecords[$key] = $record;
        }

        return $sourceRecords;
    }

    /**
     * Seed findings and their supporting evidence.
     */
    private function seedFindings(
        Research $research,
        array $findings,
        array $sourceRecords
    ): void {
        foreach ($findings as $index => $data) {
            $finding = $research->findings()->updateOrCreate(
                ['position' => $index + 1],
                $data['finding']
            );

            foreach ($data['evidence'] as $evidenceIndex => $evidence) {
                $sourceKey = $evidence['source_key'];

                $source = $sourceRecords[$sourceKey]
                    ?? throw new RuntimeException(
                        "Research source [{$sourceKey}] not found."
                    );

                $finding->evidence()->updateOrCreate(
                    ['position' => $evidenceIndex + 1],
                    [
                        'research_id' => $research->id,
                        'type' => $this->evidenceType(),
                        'reference_id' => $source->id,
                        'title' => $evidence['title'],
                        'description' => $evidence['description'],
                        'citation' => $evidence['citation'],
                    ]
                );
            }
        }
    }

    /**
     * Return a valid evidence enum case.
     *
     * Replace with the appropriate explicit case
     * once the ResearchEvidenceType enum is available.
     */
    private function evidenceType(): ResearchEvidenceType
    {
        return ResearchEvidenceType::cases()[0]
            ?? throw new RuntimeException(
                'ResearchEvidenceType must contain at least one case.'
            );
    }

    /**
     * Research seed data.
     */
    private function researches(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | 1. Artificial Intelligence Research
            |--------------------------------------------------------------------------
            */

            [
                'title' => 'The Future of Artificial Intelligence in the Workplace',

                'subtitle' => 'Understanding how AI is transforming employment, productivity and organizational structures.',

                'description' => 'An investigation into artificial intelligence adoption across industries, its influence on workforce productivity, changing job requirements and the future of professional employment.',

                'summary' => 'This research examines AI adoption, workforce transformation, emerging skills and the opportunities and challenges associated with integrating AI into modern workplaces.',

                'type' => ResearchType::Industry,
                'status' => ResearchStatus::Published,
                'featured' => true,
                'published_at' => now()->subDays(15),

                'methodology' => [
                    'method' => 'Mixed Methods Research',
                    'description' => 'Combines quantitative industry statistics with qualitative analysis of AI adoption and workforce transformation.',
                    'research_design' => 'Exploratory and descriptive research',
                    'data_collection_method' => 'Secondary research, industry reports and published surveys',
                    'sample_size' => 500,
                    'population' => 'Organizations and professionals across technology, finance, healthcare and manufacturing.',
                    'geography' => 'Global',
                    'start_date' => '2026-01-01',
                    'end_date' => '2026-06-30',
                    'limitations' => 'AI adoption differs significantly across industries and geographical regions.',
                ],

                'questions' => [
                    [
                        'question' => 'How is artificial intelligence changing traditional workplace responsibilities?',
                        'description' => 'Examine changes in job responsibilities, workflows and organizational structures.',
                        'type' => ResearchQuestionType::Primary,
                    ],
                    [
                        'question' => 'Which professional skills will become more important as AI adoption increases?',
                        'description' => 'Identify emerging technical and human skills.',
                        'type' => ResearchQuestionType::Secondary,
                    ],
                    [
                        'question' => 'What challenges prevent organizations from adopting AI?',
                        'description' => 'Investigate implementation costs, skills gaps and governance challenges.',
                        'type' => ResearchQuestionType::Hypothesis,
                    ],
                ],

                'sources' => [
                    [
                        'key' => 'wef',
                        'title' => 'Future of Jobs Report',
                        'source_type' => ResearchSourceType::Report,
                        'publisher' => 'World Economic Forum',
                        'url' => 'https://www.weforum.org/publications/the-future-of-jobs-report-2025/',
                        'published_at' => '2025-01-01',
                        'citation' => 'World Economic Forum, Future of Jobs Report 2025.',
                        'description' => 'Research on employment trends, emerging skills and workforce transformation.',
                    ],
                    [
                        'key' => 'stanford',
                        'title' => 'AI Index Report',
                        'source_type' => ResearchSourceType::Academic,
                        'publisher' => 'Stanford University',
                        'url' => 'https://aiindex.stanford.edu/',
                        'published_at' => '2025-01-01',
                        'citation' => 'Stanford Institute for Human-Centered Artificial Intelligence, AI Index.',
                        'description' => 'Research covering AI development, adoption and economic impact.',
                    ],
                ],

                'findings' => [
                    [
                        'finding' => [
                            'title' => 'AI is transforming workplace processes',
                            'summary' => 'AI is increasingly being integrated into organizational workflows.',
                            'description' => 'AI tools can support automation, information processing, decision support and productivity improvements.',
                            'type' => ResearchFindingType::Observation,
                            'confidence' => 0.90,
                        ],

                        'evidence' => [
                            [
                                'source_key' => 'wef',
                                'title' => 'Future of Jobs Report',
                                'description' => 'Industry research on workplace transformation.',
                                'citation' => 'World Economic Forum, Future of Jobs Report.',
                            ],
                        ],
                    ],

                    [
                        'finding' => [
                            'title' => 'Skills development is increasingly important',
                            'summary' => 'Professionals need to adapt to changing technical and organizational requirements.',
                            'description' => 'AI literacy, analytical thinking, communication and problem-solving are important areas for workforce development.',
                            'type' => ResearchFindingType::Insight,
                            'confidence' => 0.85,
                        ],

                        'evidence' => [
                            [
                                'source_key' => 'stanford',
                                'title' => 'AI Index Report',
                                'description' => 'Research on AI adoption and technological developments.',
                                'citation' => 'Stanford HAI, AI Index.',
                            ],
                        ],
                    ],
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | 2. Fashion Industry Research
            |--------------------------------------------------------------------------
            */

            [
                'title' => 'Global Fashion Industry: Market Trends and Consumer Behaviour',

                'subtitle' => 'An analysis of fashion consumption, digital commerce and changing consumer preferences.',

                'description' => 'This research explores global fashion industry developments, consumer purchasing behaviour, digital fashion commerce, sustainability and emerging retail models.',

                'summary' => 'A study of the changing fashion ecosystem and the influence of technology, sustainability and consumer preferences on the industry.',

                'type' => ResearchType::Trend,
                'status' => ResearchStatus::Published,
                'featured' => true,
                'published_at' => now()->subDays(10),

                'methodology' => [
                    'method' => 'Secondary Research',
                    'description' => 'Analysis of industry publications, market research and consumer behaviour studies.',
                    'research_design' => 'Descriptive and comparative research',
                    'data_collection_method' => 'Published market reports and industry analysis',
                    'sample_size' => 300,
                    'population' => 'Fashion consumers, retailers and fashion brands.',
                    'geography' => 'Global',
                    'start_date' => '2026-02-01',
                    'end_date' => '2026-07-31',
                    'limitations' => 'Consumer preferences and fashion trends vary across markets and demographics.',
                ],

                'questions' => [
                    [
                        'question' => 'How are digital platforms changing fashion purchasing behaviour?',
                        'description' => 'Examine online fashion discovery, social commerce and digital retail.',
                        'type' => ResearchQuestionType::Primary,
                    ],
                    [
                        'question' => 'How important is sustainability in fashion purchasing decisions?',
                        'description' => 'Evaluate the influence of sustainability on consumer choices.',
                        'type' => ResearchQuestionType::Secondary,
                    ],
                    [
                        'question' => 'How are emerging technologies changing the fashion industry?',
                        'description' => 'Explore AI, virtual try-ons and digital fashion experiences.',
                        'type' => ResearchQuestionType::Secondary,
                    ],
                ],

                'sources' => [
                    [
                        'key' => 'mckinsey',
                        'title' => 'The State of Fashion',
                        'source_type' => ResearchSourceType::Report,
                        'publisher' => 'McKinsey & Company',
                        'url' => 'https://www.mckinsey.com/industries/retail/our-insights/state-of-fashion',
                        'published_at' => '2025-01-01',
                        'citation' => 'McKinsey & Company, The State of Fashion.',
                        'description' => 'Annual analysis of fashion industry developments.',
                    ],
                    [
                        'key' => 'unep',
                        'title' => 'Fashion and Sustainability',
                        'source_type' => ResearchSourceType::Organization,
                        'publisher' => 'UN Environment Programme',
                        'url' => 'https://www.unep.org/',
                        'published_at' => '2025-01-01',
                        'citation' => 'United Nations Environment Programme.',
                        'description' => 'Environmental research and sustainability initiatives.',
                    ],
                ],

                'findings' => [
                    [
                        'finding' => [
                            'title' => 'Digital platforms influence fashion discovery',
                            'summary' => 'Online platforms are important channels for discovering fashion products and brands.',
                            'description' => 'Digital retail and social media create additional opportunities for fashion brands to engage consumers.',
                            'type' => ResearchFindingType::Observation,
                            'confidence' => 0.90,
                        ],

                        'evidence' => [
                            [
                                'source_key' => 'mckinsey',
                                'title' => 'The State of Fashion',
                                'description' => 'Fashion market and retail industry analysis.',
                                'citation' => 'McKinsey & Company, The State of Fashion.',
                            ],
                        ],
                    ],

                    [
                        'finding' => [
                            'title' => 'Sustainability is an important industry challenge',
                            'summary' => 'Environmental impact remains an important consideration for fashion businesses.',
                            'description' => 'Fashion companies face increasing pressure to address resource consumption, waste and supply chain transparency.',
                            'type' => ResearchFindingType::Insight,
                            'confidence' => 0.85,
                        ],

                        'evidence' => [
                            [
                                'source_key' => 'unep',
                                'title' => 'Fashion Sustainability Research',
                                'description' => 'Environmental research and sustainability initiatives.',
                                'citation' => 'UN Environment Programme.',
                            ],
                        ],
                    ],
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | 3. Digital Transformation Research
            |--------------------------------------------------------------------------
            */

            [
                'title' => 'Digital Transformation of Small Businesses in India',

                'subtitle' => 'Examining technology adoption, operational efficiency and digital competitiveness.',

                'description' => 'An investigation into how small and medium-sized businesses use digital technologies to improve operations, customer experiences and market competitiveness.',

                'summary' => 'This research explores digital adoption barriers, technology opportunities and the role of digital infrastructure in small business development.',

                'type' => ResearchType::Market,
                'status' => ResearchStatus::Draft,
                'featured' => false,
                'published_at' => null,

                'methodology' => [
                    'method' => 'Qualitative Research',
                    'description' => 'Review of digital transformation literature and SME case studies.',
                    'research_design' => 'Exploratory research',
                    'data_collection_method' => 'Literature review and case study analysis',
                    'sample_size' => 100,
                    'population' => 'Small and medium-sized enterprises.',
                    'geography' => 'India',
                    'start_date' => '2026-04-01',
                    'end_date' => '2026-09-30',
                    'limitations' => 'The research is based primarily on secondary sources and selected case studies.',
                ],

                'questions' => [
                    [
                        'question' => 'What factors influence digital technology adoption among small businesses?',
                        'description' => 'Identify the primary drivers of technology adoption.',
                        'type' => ResearchQuestionType::Primary,
                    ],
                    [
                        'question' => 'What are the major barriers to digital transformation?',
                        'description' => 'Explore cost, infrastructure and knowledge constraints.',
                        'type' => ResearchQuestionType::Secondary,
                    ],
                ],

                'sources' => [
                    [
                        'key' => 'worldbank',
                        'title' => 'Digital Development',
                        'source_type' => ResearchSourceType::Organization,
                        'publisher' => 'World Bank',
                        'url' => 'https://www.worldbank.org/en/topic/digitaldevelopment',
                        'published_at' => null,
                        'citation' => 'World Bank, Digital Development.',
                        'description' => 'Research on digital development and economic transformation.',
                    ],
                ],

                'findings' => [
                    [
                        'finding' => [
                            'title' => 'Digital infrastructure supports business development',
                            'summary' => 'Digital tools can improve business processes and customer engagement.',
                            'description' => 'Technology adoption creates opportunities for automation, improved communication and operational visibility.',
                            'type' => ResearchFindingType::Insight,
                            'confidence' => 0.80,
                        ],

                        'evidence' => [
                            [
                                'source_key' => 'worldbank',
                                'title' => 'World Bank Digital Development',
                                'description' => 'Digital infrastructure and economic development research.',
                                'citation' => 'World Bank, Digital Development.',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}