<?php

namespace Database\Seeders;

use App\Models\Report\Report;
use App\Models\Report\ReportCategory;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    use WithoutModelEvents;
    public function run(): void
    {
        $categories = ReportCategory::query()
            ->get()
            ->keyBy('slug');

        $authors = User::query()
            ->whereNotNull('username')
            ->where('is_active', true)
            ->get();

        if ($categories->isEmpty()) {
            $this->command->error('No report categories found.');
            return;
        }

        if ($authors->isEmpty()) {
            $this->command->error('No active users found.');
            return;
        }

        $reports = [
            [
                'title' => 'The Future of Work',
                'slug' => 'the-future-of-work',
                'summary' => 'How technology, changing expectations, and new models of employment are reshaping the future of work.',
                'category' => 'business-economy',
                'report_year' => 2026,
                'published_at' => now()->subDays(5),
                'featured' => true,
                'gated' => false,
                'download_count' => 842,
                'cover_image' => 'https://images.unsplash.com/photo-1497366754035-f200968a6e72?auto=format&fit=crop&w=1600&q=80',
                'status' => true,
            ],

            [
                'title' => 'Technology and Human Progress',
                'slug' => 'technology-and-human-progress',
                'summary' => 'Exploring how emerging technologies are influencing society, institutions, creativity, and the way people solve complex problems.',
                'category' => 'technology',
                'report_year' => 2026,
                'published_at' => now()->subDays(12),
                'featured' => false,
                'gated' => true,
                'download_count' => 1256,
                'cover_image' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=1600&q=80',
                'status' => true,
            ],

            [
                'title' => 'The New Global Economy',
                'slug' => 'the-new-global-economy',
                'summary' => 'A research-driven perspective on global markets, entrepreneurship, investment, and the forces transforming economic growth.',
                'category' => 'business-economy',
                'report_year' => 2026,
                'published_at' => now()->subDays(18),
                'featured' => false,
                'gated' => false,
                'download_count' => 624,
                'cover_image' => 'https://images.unsplash.com/photo-1526304640581-d334cdbbf45e?auto=format&fit=crop&w=1600&q=80',
                'status' => true,
            ],

            [
                'title' => 'Innovation Beyond Silicon Valley',
                'slug' => 'innovation-beyond-silicon-valley',
                'summary' => 'How entrepreneurs and communities around the world are building innovative companies outside traditional technology hubs.',
                'category' => 'technology',
                'report_year' => 2026,
                'published_at' => now()->subDays(25),
                'featured' => false,
                'gated' => true,
                'download_count' => 913,
                'cover_image' => 'https://images.unsplash.com/photo-1551836022-d5d88e9218df?auto=format&fit=crop&w=1600&q=80',
                'status' => true,
            ],

            [
                'title' => 'Young People and the Next Generation',
                'slug' => 'young-people-and-the-next-generation',
                'summary' => 'Understanding the ambitions, challenges, opportunities, and changing aspirations of the next generation.',
                'category' => 'society-culture',
                'report_year' => 2025,
                'published_at' => now()->subDays(35),
                'featured' => false,
                'gated' => false,
                'download_count' => 487,
                'cover_image' => 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?auto=format&fit=crop&w=1600&q=80',
                'status' => true,
            ],

            [
                'title' => 'Building Sustainable Communities',
                'slug' => 'building-sustainable-communities',
                'summary' => 'A study of communities, sustainability, infrastructure, and the ideas shaping the places where we live.',
                'category' => 'society-culture',
                'report_year' => 2025,
                'published_at' => now()->subDays(48),
                'featured' => false,
                'gated' => true,
                'download_count' => 731,
                'cover_image' => 'https://images.unsplash.com/photo-1477959858617-67f85cf4f1df?auto=format&fit=crop&w=1600&q=80',
                'status' => true,
            ],

            [
                'title' => 'Education in a Changing World',
                'slug' => 'education-in-a-changing-world',
                'summary' => 'How education systems, educators, and technology can prepare learners for a rapidly changing world.',
                'category' => 'education',
                'report_year' => 2025,
                'published_at' => now()->subDays(60),
                'featured' => false,
                'gated' => false,
                'download_count' => 1042,
                'cover_image' => 'https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=1600&q=80',
                'status' => true,
            ],

            [
                'title' => 'Leadership and Public Responsibility',
                'slug' => 'leadership-and-public-responsibility',
                'summary' => 'Examining leadership, public responsibility, governance, decision-making, and institutional trust.',
                'category' => 'politics-policy',
                'report_year' => 2025,
                'published_at' => now()->subDays(72),
                'featured' => false,
                'gated' => false,
                'download_count' => 568,
                'cover_image' => 'https://images.unsplash.com/photo-1556761175-4b46a572b786?auto=format&fit=crop&w=1600&q=80',
                'status' => true,
            ],

            [
                'title' => 'The State of Digital Society',
                'slug' => 'the-state-of-digital-society',
                'summary' => 'Examining how digital platforms and technology are transforming communication, communities, and everyday life.',
                'category' => 'society-culture',
                'report_year' => 2025,
                'published_at' => now()->subDays(90),
                'featured' => false,
                'gated' => true,
                'download_count' => 387,
                'cover_image' => 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?auto=format&fit=crop&w=1600&q=80',
                'status' => true,
            ],

            [
                'title' => 'Global Perspectives in Science',
                'slug' => 'global-perspectives-in-science',
                'summary' => 'A collection of perspectives on scientific developments, discoveries, and research shaping the future.',
                'category' => 'science-research',
                'report_year' => 2026,
                'published_at' => now()->subDays(100),
                'featured' => false,
                'gated' => false,
                'download_count' => 1458,
                'cover_image' => 'https://images.unsplash.com/photo-1532094349884-543bc11b234d?auto=format&fit=crop&w=1600&q=80',
                'status' => true,
            ],

            [
                'title' => 'The Future of Education',
                'slug' => 'the-future-of-education',
                'summary' => 'Exploring the technologies, skills, teaching models, and policies that could shape education in the coming decade.',
                'category' => 'education',
                'report_year' => 2026,
                'published_at' => now()->subDays(115),
                'featured' => false,
                'gated' => false,
                'download_count' => 352,
                'cover_image' => 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&w=1600&q=80',
                'status' => true,
            ],

            [
                'title' => 'The Next Decade of Entrepreneurship',
                'slug' => 'the-next-decade-of-entrepreneurship',
                'summary' => 'What founders, investors, and emerging markets can expect from the next decade of entrepreneurship.',
                'category' => 'business-economy',
                'report_year' => 2026,
                'published_at' => now()->subDays(130),
                'featured' => false,
                'gated' => true,
                'download_count' => 921,
                'cover_image' => 'https://images.unsplash.com/photo-1556761175-5973dc0f32e7?auto=format&fit=crop&w=1600&q=80',
                'status' => true,
            ],

            [
                'title' => 'Emerging Markets and New Opportunities',
                'slug' => 'emerging-markets-and-new-opportunities',
                'summary' => 'An analysis of emerging markets and the opportunities they present for businesses, investors, and entrepreneurs.',
                'category' => 'business-economy',
                'report_year' => 2025,
                'published_at' => now()->subDays(145),
                'featured' => false,
                'gated' => false,
                'download_count' => 645,
                'cover_image' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&w=1600&q=80',
                'status' => true,
            ],

            [
                'title' => 'The Future of Global Collaboration',
                'slug' => 'the-future-of-global-collaboration',
                'summary' => 'How organizations, communities, and institutions can collaborate across borders to solve complex global challenges.',
                'category' => 'science-research',
                'report_year' => 2025,
                'published_at' => now()->subDays(180),
                'featured' => false,
                'gated' => false,
                'download_count' => 276,
                'cover_image' => 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?auto=format&fit=crop&w=1600&q=80',
                'status' => true,
            ],

            [
                'title' => 'The Future of Public Policy',
                'slug' => 'the-future-of-public-policy',
                'summary' => 'Exploring how governments and institutions can respond to technological, economic, and social change.',
                'category' => 'politics-policy',
                'report_year' => 2026,
                'published_at' => now()->subDays(195),
                'featured' => false,
                'gated' => true,
                'download_count' => 514,
                'cover_image' => 'https://images.unsplash.com/photo-1529107386315-e1a2ed48a620?auto=format&fit=crop&w=1600&q=80',
                'status' => true,
            ],

            // Draft
            [
                'title' => 'The Future of Global Education',
                'slug' => 'the-future-of-global-education',
                'summary' => 'A forthcoming report exploring the future of education across different regions and communities.',
                'category' => 'education',
                'report_year' => 2026,
                'published_at' => null,
                'featured' => false,
                'gated' => true,
                'download_count' => 0,
                'cover_image' => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=1600&q=80',
                'status' => false,
            ],
        ];

        foreach ($reports as $data) {
            $category = $categories->get($data['category']);

            if (! $category) {
                $this->command->warn(
                    "Category [{$data['category']}] not found for {$data['title']}."
                );

                continue;
            }

            $author = $authors->random();

            Report::updateOrCreate(
                [
                    'slug' => $data['slug'],
                ],
                [
                    'title' => $data['title'],
                    'summary' => $data['summary'],
                    'cover_image' => $data['cover_image'],
                    'category_id' => $category->id,
                    'author_id' => $author->id,
                    'report_year' => $data['report_year'],
                    'published_at' => $data['published_at'],
                    'featured' => $data['featured'],
                    'gated' => $data['gated'],
                    'download_count' => $data['download_count'],
                    'status' => $data['status'],
                ]
            );
        }

        $this->command->info('Reports seeded successfully.');
    }
}