<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Technology',
                'slug' => 'technology',
                'description' => 'Technology, software, digital products and emerging technologies.',
                'children' => [
                    ['name' => 'Software Development', 'slug' => 'software-development'],
                    ['name' => 'Web Development', 'slug' => 'web-development'],
                    ['name' => 'Mobile Development', 'slug' => 'mobile-development'],
                    ['name' => 'Artificial Intelligence', 'slug' => 'artificial-intelligence'],
                    ['name' => 'Cloud Computing', 'slug' => 'cloud-computing'],
                    ['name' => 'Cybersecurity', 'slug' => 'cybersecurity'],
                    ['name' => 'DevOps', 'slug' => 'devops'],
                    ['name' => 'Data & Analytics', 'slug' => 'data-analytics'],
                    ['name' => 'Blockchain', 'slug' => 'blockchain'],
                    ['name' => 'Emerging Technology', 'slug' => 'emerging-technology'],
                ],
            ],

            [
                'name' => 'Business',
                'slug' => 'business',
                'description' => 'Business, entrepreneurship, companies and commercial strategy.',
                'children' => [
                    ['name' => 'Entrepreneurship', 'slug' => 'entrepreneurship'],
                    ['name' => 'Startups', 'slug' => 'startups'],
                    ['name' => 'Business Strategy', 'slug' => 'business-strategy'],
                    ['name' => 'Business Development', 'slug' => 'business-development'],
                    ['name' => 'Operations', 'slug' => 'operations'],
                    ['name' => 'Sales', 'slug' => 'sales'],
                    ['name' => 'Marketing', 'slug' => 'marketing'],
                    ['name' => 'Finance', 'slug' => 'finance'],
                    ['name' => 'Human Resources', 'slug' => 'human-resources'],
                    ['name' => 'Consulting', 'slug' => 'consulting'],
                ],
            ],

            [
                'name' => 'Leadership',
                'slug' => 'leadership',
                'description' => 'Leadership, management, executives and organizational growth.',
                'children' => [
                    ['name' => 'Executive Leadership', 'slug' => 'executive-leadership'],
                    ['name' => 'People Management', 'slug' => 'people-management'],
                    ['name' => 'Team Building', 'slug' => 'team-building'],
                    ['name' => 'Management', 'slug' => 'management'],
                    ['name' => 'Decision Making', 'slug' => 'decision-making'],
                    ['name' => 'Organizational Leadership', 'slug' => 'organizational-leadership'],
                ],
            ],

            [
                'name' => 'Design',
                'slug' => 'design',
                'description' => 'Design, creativity, user experience and visual communication.',
                'children' => [
                    ['name' => 'UI Design', 'slug' => 'ui-design'],
                    ['name' => 'UX Design', 'slug' => 'ux-design'],
                    ['name' => 'Product Design', 'slug' => 'product-design'],
                    ['name' => 'Graphic Design', 'slug' => 'graphic-design'],
                    ['name' => 'Brand Design', 'slug' => 'brand-design'],
                    ['name' => 'Design Systems', 'slug' => 'design-systems'],
                ],
            ],

            [
                'name' => 'Professional Development',
                'slug' => 'professional-development',
                'description' => 'Career development, professional skills and personal growth.',
                'children' => [
                    ['name' => 'Career Growth', 'slug' => 'career-growth'],
                    ['name' => 'Professional Skills', 'slug' => 'professional-skills'],
                    ['name' => 'Personal Branding', 'slug' => 'personal-branding'],
                    ['name' => 'Networking', 'slug' => 'networking'],
                    ['name' => 'Public Speaking', 'slug' => 'public-speaking'],
                    ['name' => 'Thought Leadership', 'slug' => 'thought-leadership'],
                ],
            ],

            [
                'name' => 'Investing',
                'slug' => 'investing',
                'description' => 'Investments, venture capital, private equity and financial markets.',
                'children' => [
                    ['name' => 'Venture Capital', 'slug' => 'venture-capital'],
                    ['name' => 'Private Equity', 'slug' => 'private-equity'],
                    ['name' => 'Angel Investing', 'slug' => 'angel-investing'],
                    ['name' => 'Startup Investing', 'slug' => 'startup-investing'],
                    ['name' => 'Financial Markets', 'slug' => 'financial-markets'],
                ],
            ],

            [
                'name' => 'Media & Publishing',
                'slug' => 'media-publishing',
                'description' => 'Professional publications, interviews, media and digital content.',
                'children' => [
                    ['name' => 'Interviews', 'slug' => 'interviews'],
                    ['name' => 'Articles', 'slug' => 'articles'],
                    ['name' => 'Magazines', 'slug' => 'magazines'],
                    ['name' => 'Podcasts', 'slug' => 'podcasts'],
                    ['name' => 'Video', 'slug' => 'video'],
                    ['name' => 'News & Media', 'slug' => 'news-media'],
                ],
            ],

            [
                'name' => 'Professional Recognition',
                'slug' => 'professional-recognition',
                'description' => 'Awards, achievements, credentials and professional recognition.',
                'children' => [
                    ['name' => 'Awards', 'slug' => 'awards'],
                    ['name' => 'Achievements', 'slug' => 'achievements'],
                    ['name' => 'Certifications', 'slug' => 'certifications'],
                    ['name' => 'Credentials', 'slug' => 'credentials'],
                    ['name' => 'Industry Recognition', 'slug' => 'industry-recognition'],
                ],
            ],
        ];

        foreach ($categories as $parentData) {
            $children = $parentData['children'];

            unset($parentData['children']);

            $parent = Category::updateOrCreate(
                ['slug' => $parentData['slug']],
                [
                    ...$parentData,
                    'parent_id' => null,
                    'status' => true,
                    'sort_order' => 0,
                ],
            );

            foreach ($children as $sortOrder => $childData) {
                Category::updateOrCreate(
                    ['slug' => $childData['slug']],
                    [
                        'name' => $childData['name'],
                        'description' => null,
                        'parent_id' => $parent->id,
                        'status' => true,
                        'sort_order' => $sortOrder + 1,
                    ],
                );
            }
        }
    }
}