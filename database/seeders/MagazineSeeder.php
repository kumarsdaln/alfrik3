<?php

namespace Database\Seeders;

use App\Enums\Magazine\MagazineStatus;
use App\Models\Magazine\Magazine;
use Illuminate\Database\Seeder;

class MagazineSeeder extends Seeder
{
    public function run(): void
    {
        $magazines = [
            [
                'title' => 'The Modern Journal',
                'slug' => 'the-modern-journal',
                'subtitle' => 'Ideas, people and perspectives shaping tomorrow.',
                'description' => 'An editorial publication exploring technology, business, culture, innovation and the people behind meaningful change.',
                'status' => MagazineStatus::Published,
                'featured' => true,
                'published_at' => now()->subMonths(3),
            ],
            [
                'title' => 'The Creative Review',
                'slug' => 'the-creative-review',
                'subtitle' => 'Stories from creativity, design and contemporary culture.',
                'description' => 'A publication exploring fashion, design, photography, creative industries and contemporary culture.',
                'status' => MagazineStatus::Published,
                'featured' => true,
                'published_at' => now()->subMonths(2),
            ],
            [
                'title' => 'The Business Perspective',
                'slug' => 'the-business-perspective',
                'subtitle' => 'Understanding business, leadership and entrepreneurship.',
                'description' => 'A publication covering business strategy, leadership, entrepreneurship, finance and the changing global economy.',
                'status' => MagazineStatus::Published,
                'featured' => false,
                'published_at' => now()->subMonth(),
            ],
            [
                'title' => 'The Research Edition',
                'slug' => 'the-research-edition',
                'subtitle' => 'Research, knowledge and informed perspectives.',
                'description' => 'A publication dedicated to research, analysis, industry developments and evidence-based perspectives.',
                'status' => MagazineStatus::Draft,
                'featured' => false,
                'published_at' => null,
            ],
        ];

        foreach ($magazines as $data) {
            Magazine::query()->updateOrCreate(
                ['slug' => $data['slug']],
                $data,
            );
        }
    }
}
