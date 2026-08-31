<?php

namespace Database\Seeders;

use App\Models\Research\ResearchArea;
use Illuminate\Database\Seeder;

class ResearchAreaSeeder extends Seeder
{
    public function run(): void
    {
        $areas = [
            [
                'name' => 'Economy & Markets',
                'slug' => 'economy-markets',
                'description' => 'Research on economic trends, markets, trade, investment, and the forces shaping the global economy.',
            ],
            [
                'name' => 'Technology & Innovation',
                'slug' => 'technology-innovation',
                'description' => 'Research exploring emerging technologies, digital transformation, artificial intelligence, and innovation.',
            ],
            [
                'name' => 'Society & Culture',
                'slug' => 'society-culture',
                'description' => 'Research examining communities, culture, demographics, social change, and evolving patterns of human behaviour.',
            ],
            [
                'name' => 'Politics & Policy',
                'slug' => 'politics-policy',
                'description' => 'Research and analysis covering governance, public policy, institutions, geopolitics, and political change.',
            ],
            [
                'name' => 'Education & Skills',
                'slug' => 'education-skills',
                'description' => 'Research on education, learning, workforce skills, employability, and the future of work.',
            ],
            [
                'name' => 'Science & Research',
                'slug' => 'science-research',
                'description' => 'Research covering scientific developments, research methods, discovery, and emerging fields of study.',
            ],
            [
                'name' => 'Business & Leadership',
                'slug' => 'business-leadership',
                'description' => 'Research examining businesses, leadership, organisations, entrepreneurship, and management.',
            ],
            [
                'name' => 'Environment & Sustainability',
                'slug' => 'environment-sustainability',
                'description' => 'Research on climate, sustainability, environmental change, cities, energy, and resilient development.',
            ],
        ];

        foreach ($areas as $area) {
            ResearchArea::updateOrCreate(
                ['slug' => $area['slug']],
                $area
            );
        }

        $this->command?->info(
            count($areas).' research areas seeded successfully.'
        );
    }
}