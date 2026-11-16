<?php

namespace Database\Seeders;

use App\Enums\Magazine\MagazineIssueStatus;
use App\Models\Magazine\Magazine;
use Illuminate\Database\Seeder;

class MagazineIssueSeeder extends Seeder
{
    public function run(): void
    {
        $issues = [
            'the-modern-journal' => [
                [
                    'title' => 'The Future of Innovation',
                    'slug' => 'the-future-of-innovation',
                    'subtitle' => 'Exploring ideas that are changing the world.',
                    'description' => 'An edition exploring emerging technologies, innovation, entrepreneurship and the people building the future.',
                    'volume' => 1,
                    'issue_number' => 1,
                    'cover_date' => now()->subMonths(3)->toDateString(),
                    'published_at' => now()->subMonths(3),
                    'status' => MagazineIssueStatus::Published,
                    'featured' => true,
                ],
                [
                    'title' => 'Leadership and New Perspectives',
                    'slug' => 'leadership-and-new-perspectives',
                    'subtitle' => 'Conversations about leadership and change.',
                    'description' => 'An edition featuring leadership perspectives, organizational development and professional growth.',
                    'volume' => 1,
                    'issue_number' => 2,
                    'cover_date' => now()->subMonths(2)->toDateString(),
                    'published_at' => now()->subMonths(2),
                    'status' => MagazineIssueStatus::Published,
                    'featured' => false,
                ],
            ],

            'the-creative-review' => [
                [
                    'title' => 'The Art of Creative Expression',
                    'slug' => 'the-art-of-creative-expression',
                    'subtitle' => 'A closer look at modern creative culture.',
                    'description' => 'An edition dedicated to creative professionals, contemporary design, fashion and visual storytelling.',
                    'volume' => 1,
                    'issue_number' => 1,
                    'cover_date' => now()->subMonths(2)->toDateString(),
                    'published_at' => now()->subMonths(2),
                    'status' => MagazineIssueStatus::Published,
                    'featured' => true,
                ],
            ],

            'the-business-perspective' => [
                [
                    'title' => 'Building Sustainable Businesses',
                    'slug' => 'building-sustainable-businesses',
                    'subtitle' => 'Strategy, leadership and long-term growth.',
                    'description' => 'An edition examining business models, leadership decisions, entrepreneurship and sustainable growth.',
                    'volume' => 1,
                    'issue_number' => 1,
                    'cover_date' => now()->subMonth()->toDateString(),
                    'published_at' => now()->subMonth(),
                    'status' => MagazineIssueStatus::Published,
                    'featured' => false,
                ],
            ],

            'the-research-edition' => [
                [
                    'title' => 'Research and the Changing World',
                    'slug' => 'research-and-the-changing-world',
                    'subtitle' => 'Ideas and evidence for a changing world.',
                    'description' => 'An upcoming edition exploring research practices, knowledge development and emerging industry questions.',
                    'volume' => 1,
                    'issue_number' => 1,
                    'cover_date' => now()->addMonth()->toDateString(),
                    'published_at' => null,
                    'status' => MagazineIssueStatus::Draft,
                    'featured' => false,
                ],
            ],
        ];

        foreach ($issues as $magazineSlug => $magazineIssues) {
            $magazine = Magazine::query()
                ->where('slug', $magazineSlug)
                ->firstOrFail();

            foreach ($magazineIssues as $data) {
                $magazine->issues()->updateOrCreate(
                    ['slug' => $data['slug']],
                    $data,
                );
            }
        }
    }
}
