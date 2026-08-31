<?php

namespace Database\Seeders;

use App\Models\Event\EventCategory;
use Illuminate\Database\Seeder;

class EventCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Conferences',
                'slug' => 'conferences',
            ],
            [
                'name' => 'Workshops',
                'slug' => 'workshops',
            ],
            [
                'name' => 'Panel Discussions',
                'slug' => 'panel-discussions',
            ],
            [
                'name' => 'Seminars',
                'slug' => 'seminars',
            ],
            [
                'name' => 'Public Lectures',
                'slug' => 'public-lectures',
            ],
            [
                'name' => 'Networking',
                'slug' => 'networking',
            ],
        ];

        foreach ($categories as $category) {
            EventCategory::updateOrCreate(
                ['slug' => $category['slug']],
                $category,
            );
        }
    }
}