<?php

namespace Database\Seeders;

use App\Models\Interview\Interview;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InterviewSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $interviews = [
                [
                    'title' => 'Building the Future of Artificial Intelligence',
                    'slug' => 'building-the-future-of-artificial-intelligence',
                    'description' => 'An in-depth conversation about artificial intelligence, innovation, and the future of technology.',
                    'introduction' => <<<'TEXT'
Artificial intelligence is transforming how businesses operate, how people work, and how society approaches complex problems.

In this interview, we explore the opportunities, challenges, and responsibilities associated with building AI-powered technologies.
TEXT,
                    'conclusion' => <<<'TEXT'
The future of artificial intelligence will depend not only on technological progress but also on responsible development, transparency, and meaningful human oversight.
TEXT,
                    'interview_type' => 'written',
                    'status' => 'draft',
                    'questions' => [
                        [
                            'question' => 'How would you describe the current state of artificial intelligence?',
                            'answers' => [
                                'Artificial intelligence is moving beyond experimental applications. It is becoming part of everyday business operations, software development, research, and decision-making. The most important shift is that AI is increasingly being integrated into practical workflows.',
                            ],
                        ],
                        [
                            'question' => 'What industries do you believe will experience the greatest transformation?',
                            'answers' => [
                                'Healthcare, education, finance, manufacturing, and software development are likely to experience significant changes. However, the impact will depend on how effectively organizations integrate AI into their existing processes.',
                            ],
                        ],
                        [
                            'question' => 'What are the biggest challenges facing AI adoption?',
                            'answers' => [
                                'Data quality, privacy, security, reliability, and a lack of technical expertise remain important challenges. Organizations also need to understand that AI systems require continuous evaluation and human oversight.',
                            ],
                        ],
                        [
                            'question' => 'What advice would you give to someone starting a career in AI?',
                            'answers' => [
                                'Build strong fundamentals in programming, mathematics, data structures, and machine learning. More importantly, learn to solve real problems rather than focusing exclusively on individual tools or frameworks.',
                            ],
                        ],
                    ],
                ],
                [
                    'title' => 'Entrepreneurship, Leadership and Building Businesses',
                    'slug' => 'entrepreneurship-leadership-and-building-businesses',
                    'description' => 'A conversation about entrepreneurship, leadership, business development, and lessons from building organizations.',
                    'introduction' => <<<'TEXT'
Building a business requires more than a good idea. It involves understanding customers, making difficult decisions, managing uncertainty, and creating something that delivers lasting value.

This interview explores the realities of entrepreneurship and the principles that help businesses grow.
TEXT,
                    'conclusion' => <<<'TEXT'
Successful entrepreneurship is a continuous process of learning, adapting, and creating value. Sustainable businesses are built through consistent execution rather than short-term excitement.
TEXT,
                    'interview_type' => 'written',
                    'status' => 'draft',
                    'questions' => [
                        [
                            'question' => 'What motivated you to become an entrepreneur?',
                            'answers' => [
                                'The opportunity to solve meaningful problems and build something independently was a major motivation. Entrepreneurship provides the freedom to experiment, take responsibility, and turn ideas into practical solutions.',
                            ],
                        ],
                        [
                            'question' => 'What is the biggest mistake new entrepreneurs make?',
                            'answers' => [
                                'One common mistake is building a product before understanding the customer. Entrepreneurs should validate the problem, speak with potential customers, and test their assumptions before investing heavily in development.',
                            ],
                        ],
                        [
                            'question' => 'How important is leadership when building a company?',
                            'answers' => [
                                'Leadership determines how people work together, make decisions, and respond to challenges. A good leader creates clarity, builds trust, and helps the team remain focused on shared objectives.',
                            ],
                        ],
                        [
                            'question' => 'What advice would you give to someone starting their first business?',
                            'answers' => [
                                'Start with a clearly defined problem, keep expenses under control, speak with customers frequently, and focus on generating sustainable revenue. Learn from feedback and be willing to change your approach.',
                            ],
                        ],
                    ],
                ],
            ];

            foreach ($interviews as $data) {
                $questions = $data['questions'];

                unset($data['questions']);

                $interview = Interview::updateOrCreate(
                    ['slug' => $data['slug']],
                    $data,
                );

                foreach ($questions as $questionPosition => $questionData) {
                    $answers = $questionData['answers'];

                    unset($questionData['answers']);

                    $question = $interview->questions()->updateOrCreate(
                        ['position' => $questionPosition + 1],
                        [
                            'question' => $questionData['question'],
                            'asked_by' => null,
                        ],
                    );

                    foreach ($answers as $answerPosition => $answer) {
                        $question->answers()->updateOrCreate(
                            ['position' => $answerPosition + 1],
                            [
                                'answer' => $answer,
                                'answered_by' => null,
                            ],
                        );
                    }
                }
            }
        });
    }
}
