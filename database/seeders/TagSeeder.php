<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            'Technology',
            'Software',
            'Programming',
            'Web Development',
            'Mobile Development',
            'Artificial Intelligence',
            'Machine Learning',
            'Cloud Computing',
            'Cybersecurity',
            'DevOps',
            'Data Science',
            'Data Analytics',
            'Blockchain',
            'SaaS',
            'Enterprise Technology',

            'Business',
            'Entrepreneurship',
            'Startup',
            'Founder',
            'Co-Founder',
            'Business Strategy',
            'Business Development',
            'Operations',
            'Sales',
            'Marketing',
            'Digital Marketing',
            'Finance',
            'FinTech',
            'Consulting',

            'Leadership',
            'CEO',
            'CTO',
            'Executive',
            'Management',
            'Team Building',
            'People Management',
            'Decision Making',
            'Organizational Growth',

            'Design',
            'UI Design',
            'UX Design',
            'Product Design',
            'Graphic Design',
            'Branding',
            'Design Systems',

            'Career',
            'Career Growth',
            'Professional Development',
            'Personal Branding',
            'Networking',
            'Public Speaking',
            'Thought Leadership',
            'Mentorship',

            'Investing',
            'Investor',
            'Venture Capital',
            'Private Equity',
            'Angel Investor',
            'Startup Investment',

            'Creator',
            'Content Creator',
            'Content Strategy',
            'Creator Economy',
            'Publishing',
            'Media',
            'Interview',
            'Podcast',
            'Video',
            'Magazine',

            'Awards',
            'Achievement',
            'Recognition',
            'Certification',
            'Credential',
            'Professional Excellence',

            'Innovation',
            'Digital Transformation',
            'Future of Work',
            'Industry Trends',
            'Emerging Technology',
            'Productivity',
            'Remote Work',
            'Collaboration',
            'Community',
        ];

        foreach ($tags as $name) {
            Tag::updateOrCreate(
                ['slug' => str($name)->slug()],
                [
                    'name' => $name,
                    'description' => null,
                    'status' => true,
                ],
            );
        }
    }
}