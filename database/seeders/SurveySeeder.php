<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Survey\Survey;
use App\Models\Survey\SurveyAnswer;
use App\Models\Survey\SurveyOption;
use App\Models\Survey\SurveyQuestion;
use App\Models\Survey\SurveyResponse;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SurveySeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $users = User::query()
                ->orderBy('id')
                ->get();

            if ($users->isEmpty()) {
                $this->command?->warn(
                    'No users found. Please seed users first.'
                );

                return;
            }

            $author = $users->first();

            /*
             * -------------------------------------------------------------
             * Survey 1
             * -------------------------------------------------------------
             */

            $survey = Survey::updateOrCreate(
                [
                    'slug' => 'future-of-work-2026',
                ],
                [
                    'title' => 'The Future of Work 2026',
                    'description' => 'Share your perspective on how technology, remote work, and changing expectations are shaping the future of work.',
                    'status' => true,
                    'published_at' => now()->subDays(10),
                    'closes_at' => now()->addDays(30),
                    'allow_anonymous' => true,
                    'one_response_per_user' => true,
                    'show_results' => true,
                    'author_id' => $author->id,
                ]
            );

            $questions = [
                [
                    'question' => 'How optimistic are you about the future of work?',
                    'type' => 'single_choice',
                    'required' => true,
                    'position' => 1,
                    'options' => [
                        'Very optimistic',
                        'Somewhat optimistic',
                        'Neutral',
                        'Somewhat concerned',
                        'Very concerned',
                    ],
                ],
                [
                    'question' => 'Which changes will have the biggest impact on your work?',
                    'type' => 'multiple_choice',
                    'required' => true,
                    'position' => 2,
                    'options' => [
                        'Artificial intelligence',
                        'Remote work',
                        'Automation',
                        'Flexible working hours',
                        'Digital collaboration',
                        'Changing skill requirements',
                    ],
                ],
                [
                    'question' => 'What is the biggest challenge you expect workers to face?',
                    'type' => 'text',
                    'required' => true,
                    'position' => 3,
                    'settings' => [
                        'placeholder' => 'Share your thoughts...',
                        'max_length' => 1000,
                    ],
                ],
                [
                    'question' => 'How prepared do you feel for the changing workplace?',
                    'type' => 'rating',
                    'required' => true,
                    'position' => 4,
                    'settings' => [
                        'min' => 1,
                        'max' => 5,
                        'labels' => [
                            '1' => 'Not prepared',
                            '5' => 'Very prepared',
                        ],
                    ],
                ],
            ];

            $this->createQuestions($survey, $questions);

            /*
             * -------------------------------------------------------------
             * Survey 2
             * -------------------------------------------------------------
             */

            $survey = Survey::updateOrCreate(
                [
                    'slug' => 'digital-media-consumption',
                ],
                [
                    'title' => 'Digital Media & Information',
                    'description' => 'A survey exploring how people discover, consume, and evaluate news and information online.',
                    'status' => true,
                    'published_at' => now()->subDays(20),
                    'closes_at' => now()->subDays(2),
                    'allow_anonymous' => false,
                    'one_response_per_user' => true,
                    'show_results' => true,
                    'author_id' => $author->id,
                ]
            );

            $questions = [
                [
                    'question' => 'Where do you most often discover news?',
                    'type' => 'single_choice',
                    'required' => true,
                    'position' => 1,
                    'options' => [
                        'News websites',
                        'Social media',
                        'Search engines',
                        'Newsletters',
                        'YouTube',
                        'Friends and colleagues',
                    ],
                ],
                [
                    'question' => 'Which formats do you regularly consume?',
                    'type' => 'multiple_choice',
                    'required' => false,
                    'position' => 2,
                    'options' => [
                        'Articles',
                        'Videos',
                        'Podcasts',
                        'Newsletters',
                        'Infographics',
                        'Social posts',
                    ],
                ],
                [
                    'question' => 'How much do you trust information you find on social media?',
                    'type' => 'rating',
                    'required' => true,
                    'position' => 3,
                    'settings' => [
                        'min' => 1,
                        'max' => 5,
                        'labels' => [
                            '1' => 'Do not trust',
                            '5' => 'Trust completely',
                        ],
                    ],
                ],
                [
                    'question' => 'What would make online journalism more trustworthy?',
                    'type' => 'text',
                    'required' => false,
                    'position' => 4,
                    'settings' => [
                        'placeholder' => 'Tell us what you think...',
                        'max_length' => 1000,
                    ],
                ],
            ];

            $this->createQuestions($survey, $questions);

            /*
             * -------------------------------------------------------------
             * Survey 3 - Draft
             * -------------------------------------------------------------
             */

            $survey = Survey::updateOrCreate(
                [
                    'slug' => 'young-entrepreneurs',
                ],
                [
                    'title' => 'Young Entrepreneurs & the Next Generation',
                    'description' => 'A survey exploring the ambitions, challenges, and expectations of young entrepreneurs.',
                    'status' => false,
                    'published_at' => null,
                    'closes_at' => null,
                    'allow_anonymous' => true,
                    'one_response_per_user' => false,
                    'show_results' => false,
                    'author_id' => $author->id,
                ]
            );

            $questions = [
                [
                    'question' => 'What motivates you most to start a business?',
                    'type' => 'single_choice',
                    'required' => true,
                    'position' => 1,
                    'options' => [
                        'Financial independence',
                        'Solving a problem',
                        'Creative freedom',
                        'Social impact',
                        'Building something of my own',
                    ],
                ],
                [
                    'question' => 'What is the biggest barrier to entrepreneurship?',
                    'type' => 'text',
                    'required' => true,
                    'position' => 2,
                    'settings' => [
                        'placeholder' => 'Describe the biggest barrier...',
                        'max_length' => 1000,
                    ],
                ],
            ];

            $this->createQuestions($survey, $questions);

            /*
             * -------------------------------------------------------------
             * Sample Responses
             * -------------------------------------------------------------
             */

            $openSurvey = Survey::where(
                'slug',
                'future-of-work-2026'
            )->first();

            $this->createSampleResponses($openSurvey, $users);
        });

        $this->command?->info(
            'Surveys, questions, options and sample responses seeded successfully.'
        );
    }

    private function createQuestions(
        Survey $survey,
        array $questions
    ): void {
        foreach ($questions as $data) {
            $options = $data['options'] ?? null;

            unset($data['options']);

            $question = SurveyQuestion::updateOrCreate(
                [
                    'survey_id' => $survey->id,
                    'position' => $data['position'],
                ],
                $data
            );

            if ($options) {
                foreach ($options as $position => $label) {
                    SurveyOption::updateOrCreate(
                        [
                            'question_id' => $question->id,
                            'position' => $position + 1,
                        ],
                        [
                            'label' => $label,
                        ]
                    );
                }
            }
        }
    }

    private function createSampleResponses(
        Survey $survey,
        $users
    ): void {
        if (! $survey) {
            return;
        }

        $respondents = $users->take(3);

        foreach ($respondents as $index => $user) {
            $response = SurveyResponse::updateOrCreate(
                [
                    'survey_id' => $survey->id,
                    'user_id' => $user->id,
                ],
                [
                    'session_token' => null,
                    'ip_address' => null,
                ]
            );

            $questions = $survey->questions()
                ->with('options')
                ->get();

            foreach ($questions as $question) {
                switch ($question->type) {
                    case 'single_choice':
                        $option = $question->options->get(
                            $index % max($question->options->count(), 1)
                        );

                        if ($option) {
                            SurveyAnswer::updateOrCreate(
                                [
                                    'response_id' => $response->id,
                                    'question_id' => $question->id,
                                ],
                                [
                                    'option_id' => $option->id,
                                    'value_text' => null,
                                ]
                            );
                        }

                        break;

                    case 'multiple_choice':
                        $options = $question->options
                            ->take($index === 0 ? 2 : 3);

                        foreach ($options as $option) {
                            SurveyAnswer::updateOrCreate(
                                [
                                    'response_id' => $response->id,
                                    'question_id' => $question->id,
                                    'option_id' => $option->id,
                                ],
                                [
                                    'value_text' => null,
                                ]
                            );
                        }

                        break;

                    case 'text':
                        SurveyAnswer::updateOrCreate(
                            [
                                'response_id' => $response->id,
                                'question_id' => $question->id,
                            ],
                            [
                                'option_id' => null,
                                'value_text' => match ($index) {
                                    0 => 'The biggest challenge will be adapting to new technologies while continuing to develop human skills.',
                                    1 => 'Keeping skills relevant as automation changes the way people work.',
                                    default => 'Finding the right balance between technology, productivity, and meaningful work.',
                                },
                            ]
                        );

                        break;

                    case 'rating':
                        SurveyAnswer::updateOrCreate(
                            [
                                'response_id' => $response->id,
                                'question_id' => $question->id,
                            ],
                            [
                                'option_id' => null,
                                'value_text' => (string) (3 + min($index, 2)),
                            ]
                        );

                        break;
                }
            }
        }
    }
}