<?php

namespace Database\Seeders;

use App\Models\Report\ReportCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReportCategorySeeder extends Seeder
{
    use WithoutModelEvents;
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Business & Economy',
                'description' => 'Research and reports covering business, markets, and economic developments.',
            ],
            [
                'name' => 'Technology',
                'description' => 'Research covering technology, innovation, digital transformation, and emerging technologies.',
            ],
            [
                'name' => 'Society & Culture',
                'description' => 'Research exploring society, culture, communities, and changing social trends.',
            ],
            [
                'name' => 'Politics & Policy',
                'description' => 'Research and analysis covering politics, governance, and public policy.',
            ],
            [
                'name' => 'Education',
                'description' => 'Research covering education, learning, skills, and the future of work.',
            ],
            [
                'name' => 'Science & Research',
                'description' => 'Research reports covering scientific developments and important research findings.',
            ],
        ];

        foreach ($categories as $category) {
            ReportCategory::updateOrCreate(
                [
                    'slug' => ReportCategory::uniqueSlug(
                        $category['name']
                    ),
                ],
                [
                    'name' => $category['name'],
                    'description' => $category['description'],
                ]
            );
        }
    }
}
