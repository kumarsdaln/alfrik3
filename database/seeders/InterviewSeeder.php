<?php

namespace Database\Seeders;

use App\Enums\Interview\Status;
use App\Enums\Interview\Type;
use App\Models\Interview\Interview;
use App\Models\Interview\InterviewAnswer;
use App\Models\Interview\InterviewMedia;
use App\Models\Interview\InterviewParticipant;
use App\Models\Interview\InterviewQuestion;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class InterviewSeeder extends Seeder
{
    use WithoutModelEvents; 
    public function run(): void
    {
        $users = User::query()
            ->where('is_active', true)
            ->whereNotNull('username')
            ->take(10)
            ->get();

        if ($users->count() < 3) {
            $this->command->warn(
                'At least 3 active users with usernames are required.'
            );

            return;
        }

        $interviewer = $users->first();

        $interviewees = $users
            ->skip(1)
            ->take(2)
            ->values();

        $interviews = [
            [
                'title' => 'Building Technology for the Next Generation',
                'slug' => 'building-technology-for-the-next-generation',
                'description' => 'A conversation about technology, innovation, leadership, and building products that create meaningful impact.',
                'type' => Type::VIDEO,
                'duration' => 1840,
                'thumbnail' => 'https://images.unsplash.com/photo-1556761175-b413da4baf72?auto=format&fit=crop&w=1600&q=80',
                'embed_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            ],
            [
                'title' => 'The Future of Entrepreneurship',
                'slug' => 'the-future-of-entrepreneurship',
                'description' => 'An in-depth conversation about entrepreneurship, business growth, creativity, and the changing world of work.',
                'type' => Type::AUDIO,
                'duration' => 2460,
                'thumbnail' => 'https://images.unsplash.com/photo-1551836022-d5d88e9218df?auto=format&fit=crop&w=1600&q=80',
                'embed_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            ],
            [
                'title' => 'From Ideas to Impact',
                'slug' => 'from-ideas-to-impact',
                'description' => 'How ambitious ideas become real products, companies, and movements.',
                'type' => Type::WRITTEN,
                'duration' => null,
                'thumbnail' => 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?auto=format&fit=crop&w=1600&q=80',
                'embed_url' => null,
            ],
        ];

        foreach ($interviews as $data) {
            /*
            |--------------------------------------------------------------------------
            | Interview
            |--------------------------------------------------------------------------
            */

            $interview = Interview::updateOrCreate(
                [
                    'slug' => $data['slug'],
                ],
                [
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'interview_type' => $data['type'],
                    'status' => Status::PUBLISHED,
                    'thumbnail' => $data['thumbnail'],
                    'duration' => $data['duration'],
                    'published_at' => Carbon::now()->subDays(
                        fake()->numberBetween(1, 30)
                    ),
                    'created_by' => $interviewer->id,
                ],
            );

            /*
            |--------------------------------------------------------------------------
            | Participants
            |--------------------------------------------------------------------------
            */

            $interview->participants()->delete();

            InterviewParticipant::create([
                'interview_id' => $interview->id,
                'user_id' => $interviewer->id,
                'role' => 'interviewer',
            ]);

            foreach ($interviewees as $user) {
                InterviewParticipant::create([
                    'interview_id' => $interview->id,
                    'user_id' => $user->id,
                    'role' => 'interviewee',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Media
            |--------------------------------------------------------------------------
            */

            $interview->media()->delete();

            if ($data['type'] === Type::VIDEO) {
                InterviewMedia::create([
                    'interview_id' => $interview->id,
                    'media_type' => 'video',
                    'source_type' => 'external',
                    'file_url' => null,
                    'embed_url' => $data['embed_url'],
                    'duration' => $data['duration'],
                    'thumbnail' => $data['thumbnail'],
                ]);
            }

            if ($data['type'] === Type::AUDIO) {
                InterviewMedia::create([
                    'interview_id' => $interview->id,
                    'media_type' => 'audio',
                    'source_type' => 'external',
                    'file_url' => null,
                    'embed_url' => $data['embed_url'],
                    'duration' => $data['duration'],
                    'thumbnail' => $data['thumbnail'],
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Questions
            |--------------------------------------------------------------------------
            */

            $interview->questions()->delete();

            $questions = [
                'What inspired you to start working in your field?',
                'What has been the biggest challenge you have faced?',
                'How do you approach innovation and creativity?',
                'What advice would you give to people starting today?',
                'Where do you see your industry going next?',
            ];

            foreach ($questions as $index => $questionText) {
                $question = InterviewQuestion::create([
                    'interview_id' => $interview->id,
                    'asked_by' => $interviewer->id,
                    'question' => $questionText,
                    'order' => $index + 1,
                ]);

                /*
                |--------------------------------------------------------------------------
                | Answer
                |--------------------------------------------------------------------------
                */

                $answeredBy = $interviewees[
                    $index % $interviewees->count()
                ];

                InterviewAnswer::create([
                    'question_id' => $question->id,
                    'answered_by' => $answeredBy->id,
                    'answer' => $this->answer($index),
                ]);
            }
        }

        $this->command->info(
            count($interviews) . ' interviews seeded successfully.'
        );
    }

    private function answer(int $index): string
    {
        return match ($index) {
            0 => '<p>I have always been fascinated by the intersection of people, technology, and ideas. That curiosity eventually became the foundation for the work I do today.</p>',

            1 => '<p>The biggest challenge has been learning how to make difficult decisions with incomplete information. Experience has taught me that progress rarely comes from waiting for perfect certainty.</p>',

            2 => '<p>For me, innovation starts with understanding the problem deeply. Once the problem is clear, technology becomes a tool for creating a better solution.</p>',

            3 => '<p>Start before you feel ready. Build something, learn from people, and keep improving. The most valuable lessons usually come from doing the work.</p>',

            default => '<p>I believe the future will be shaped by people who can combine technology with creativity, empathy, and a strong understanding of the communities they serve.</p>',
        };
    }
}