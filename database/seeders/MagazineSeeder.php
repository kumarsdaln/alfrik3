<?php

namespace Database\Seeders;

use App\Models\Magazine\Magazine;
use App\Models\Magazine\MagazineArticle;
use App\Models\Magazine\MagazineCategory;
use App\Models\Magazine\MagazineIssue;
use App\Models\User;
use Illuminate\Database\Seeder;

class MagazineSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Author
        |--------------------------------------------------------------------------
        */

        $author = User::query()->first();

        /*
        |--------------------------------------------------------------------------
        | Magazine Category
        |--------------------------------------------------------------------------
        */

        $category = MagazineCategory::updateOrCreate(
            [
                'slug' => 'business',
            ],
            [
                'name' => 'Business',
                'icon' => 'briefcase',
                'description' => 'Business, entrepreneurship, leadership, markets, and the ideas shaping modern organisations.',
                'status' => true,
                'position' => 1,

                'meta_title' => 'Business Magazine — Alfrik',

                'meta_description' => 'Explore business, entrepreneurship, leadership, markets, and organisational insights from Alfrik.',

                'meta_keywords' => 'business, entrepreneurship, leadership, markets, organisations',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Magazine
        |--------------------------------------------------------------------------
        */

        $magazine = Magazine::updateOrCreate(
            [
                'slug' => 'alfrik-magazine',
            ],
            [
                'title' => 'Alfrik Magazine',

                'subtitle' => 'Ideas, people and perspectives shaping the world.',

                'content' => json_encode([
                    'intro' => 'Alfrik Magazine brings together thoughtful journalism, interviews, research and analysis covering business, technology, culture and society.',
                ]),

                'cover_image' => 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?auto=format&fit=crop&w=1600&q=85',

                'category_id' => $category->id,

                'author_id' => $author?->id,

                'status' => true,

                'published_at' => now(),

                'meta_title' => 'Alfrik Magazine — Ideas, People & Perspectives',

                'meta_description' => 'Discover interviews, analysis, stories and perspectives from Alfrik Magazine.',

                'meta_keywords' => 'Alfrik Magazine, business, technology, culture, interviews, analysis',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Issue 01
        |--------------------------------------------------------------------------
        */

        $issue = MagazineIssue::updateOrCreate(
            [
                'slug' => 'volume-1-issue-1',
            ],
            [
                /*
                | IMPORTANT:
                | magazine_id is required by the current database schema.
                */

                'magazine_id' => $magazine->id,

                'title' => 'The Future of Global Business',

                'slug' => 'volume-1-issue-1',

                'subtitle' => 'Technology, leadership and changing markets are reshaping modern organisations.',

                'volume' => 1,

                'issue_number' => 1,

                'cover_date' => '2026-08-01',

                'published_at' => '2026-08-01 09:00:00',

                'description' => 'The inaugural issue of Alfrik Magazine explores the forces transforming business, technology, leadership and modern society.',

                'editor' => 'Alfrik Editorial',

                'cover_image' => 'https://images.unsplash.com/photo-1497366811353-6870744d04b2?auto=format&fit=crop&w=1600&q=85',

                'file_path' => null,

                'file_size' => null,

                'file_type' => 'PDF',

                'download_count' => 128,

                'status' => true,

                'featured' => true,

                'meta_title' => 'The Future of Global Business — Alfrik Magazine',

                'meta_description' => 'The first issue of Alfrik Magazine explores technology, leadership, innovation and the future of global business.',

                'meta_keywords' => 'Alfrik Magazine, business, technology, leadership, innovation, global business',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Article 01
        |--------------------------------------------------------------------------
        */

        MagazineArticle::updateOrCreate(
            [
                'slug' => 'why-global-business-is-changing-faster-than-ever',
            ],
            [
                'issue_id' => $issue->id,

                /*
                | No category_id here.
                | Category belongs to the Magazine.
                */

                'author_id' => $author?->id,

                'title' => 'Why Global Business Is Changing Faster Than Ever',

                'subtitle' => 'The forces reshaping international business.',

                'excerpt' => 'Technology, changing customer expectations and geopolitical shifts are creating a new global business environment.',

                'content' => '
                    <p>The global business environment is changing at an extraordinary pace.</p>

                    <p>Technology is transforming how organisations operate, while changing customer expectations are forcing companies to rethink their products and services.</p>

                    <p>Artificial intelligence, automation and data-driven decision making are becoming increasingly important to modern organisations.</p>

                    <p>The businesses most prepared for the future will be those capable of adapting quickly while maintaining a clear sense of purpose.</p>
                ',

                'cover_image' => 'https://images.unsplash.com/photo-1556761175-b413da4baf72?auto=format&fit=crop&w=1600&q=85',

                'type' => 'article',

                'byline' => 'Alfrik Editorial',

                'position' => 1,

                'featured' => true,

                'status' => true,

                'published_at' => '2026-08-01 10:00:00',

                'views' => 1240,

                'reading_time' => 7,

                'meta_title' => 'Why Global Business Is Changing Faster Than Ever — Alfrik',

                'meta_description' => 'An analysis of the forces transforming global business.',

                'meta_keywords' => 'business, global business, leadership, markets, technology',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Article 02
        |--------------------------------------------------------------------------
        */

        MagazineArticle::updateOrCreate(
            [
                'slug' => 'the-new-era-of-business-leadership',
            ],
            [
                'issue_id' => $issue->id,

                'author_id' => $author?->id,

                'title' => 'The New Era of Business Leadership',

                'subtitle' => 'What modern organisations expect from their leaders.',

                'excerpt' => 'Leadership is evolving as organisations become more distributed, technology-driven and globally connected.',

                'content' => '
                    <p>Leadership is entering a new era.</p>

                    <p>Traditional approaches built around hierarchy and control are increasingly being replaced by models centred on collaboration, adaptability and trust.</p>

                    <p>Modern leaders must understand technology while also maintaining a strong focus on people and organisational culture.</p>

                    <p>The ability to make decisions in uncertain environments may become one of the defining characteristics of successful leadership.</p>
                ',

                'cover_image' => 'https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&w=1600&q=85',

                'type' => 'analysis',

                'byline' => 'Alfrik Editorial',

                'position' => 2,

                'featured' => false,

                'status' => true,

                'published_at' => '2026-08-02 09:00:00',

                'views' => 864,

                'reading_time' => 6,

                'meta_title' => 'The New Era of Business Leadership — Alfrik',

                'meta_description' => 'An exploration of how leadership is changing in modern organisations.',

                'meta_keywords' => 'leadership, business leadership, management, organisations, Alfrik',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Article 03
        |--------------------------------------------------------------------------
        */

        MagazineArticle::updateOrCreate(
            [
                'slug' => 'technology-and-the-future-of-work',
            ],
            [
                'issue_id' => $issue->id,

                'author_id' => $author?->id,

                'title' => 'Technology and the Future of Work',

                'subtitle' => 'How automation and artificial intelligence are changing the workplace.',

                'excerpt' => 'The future of work will be shaped by the relationship between people, automation and artificial intelligence.',

                'content' => '
                    <p>Technology is changing the workplace faster than many organisations anticipated.</p>

                    <p>Automation is taking over repetitive processes while artificial intelligence is increasingly being used to support research, analysis and decision making.</p>

                    <p>However, technological progress does not necessarily eliminate the importance of human skills.</p>

                    <p>Creativity, communication, judgement and leadership will remain essential as organisations adapt to increasingly sophisticated technologies.</p>
                ',

                'cover_image' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=1600&q=85',

                'type' => 'feature',

                'byline' => 'Alfrik Editorial',

                'position' => 3,

                'featured' => false,

                'status' => true,

                'published_at' => '2026-08-03 09:00:00',

                'views' => 692,

                'reading_time' => 8,

                'meta_title' => 'Technology and the Future of Work — Alfrik',

                'meta_description' => 'How artificial intelligence and automation are transforming the future of work.',

                'meta_keywords' => 'technology, artificial intelligence, automation, future of work, business',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Article 04
        |--------------------------------------------------------------------------
        */

        MagazineArticle::updateOrCreate(
            [
                'slug' => 'building-businesses-that-last',
            ],
            [
                'issue_id' => $issue->id,

                'author_id' => $author?->id,

                'title' => 'Building Businesses That Last',

                'subtitle' => 'Why long-term thinking matters in a rapidly changing economy.',

                'excerpt' => 'Sustainable organisations are built around resilience, responsible decision making and a clear understanding of their customers.',

                'content' => '
                    <p>Building a successful business is not simply about achieving rapid growth.</p>

                    <p>Organisations that survive changing economic conditions tend to focus on resilience, customer relationships and disciplined decision making.</p>

                    <p>Long-term thinking can help businesses navigate uncertainty while creating lasting value for customers, employees and communities.</p>

                    <p>The strongest organisations are often those that can balance innovation with consistency.</p>
                ',

                'cover_image' => 'https://images.unsplash.com/photo-1556761175-5973dc0f32e7?auto=format&fit=crop&w=1600&q=85',

                'type' => 'opinion',

                'byline' => 'Alfrik Editorial',

                'position' => 4,

                'featured' => false,

                'status' => true,

                'published_at' => '2026-08-04 09:00:00',

                'views' => 518,

                'reading_time' => 5,

                'meta_title' => 'Building Businesses That Last — Alfrik',

                'meta_description' => 'Why resilience, responsible decision making and long-term thinking matter for modern businesses.',

                'meta_keywords' => 'business, entrepreneurship, resilience, sustainability, leadership',
            ]
        );
    }
}