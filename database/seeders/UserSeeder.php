<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Industry;
use App\Models\Language;
use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            'IN' => Country::where('code', 'IN')->value('id'),
            'US' => Country::where('code', 'US')->value('id'),
            'GB' => Country::where('code', 'GB')->value('id'),
            'ES' => Country::where('code', 'ES')->value('id'),
            'IT' => Country::where('code', 'IT')->value('id'),
            'CN' => Country::where('code', 'CN')->value('id'),
        ];

        $languages = [
            'en' => Language::where('code', 'en')->value('id'),
            'hi' => Language::where('code', 'hi')->value('id'),
            'es' => Language::where('code', 'es')->value('id'),
            'fr' => Language::where('code', 'fr')->value('id'),
            'zh' => Language::where('code', 'zh')->value('id'),
            'it' => Language::where('code', 'it')->value('id'),
        ];

        $positions = [
            'software_engineer' => Position::where('name', 'Software Engineer')->value('id'),
            'product_designer' => Position::where('name', 'Product Designer')->value('id'),
            'founder' => Position::where('name', 'Founder')->value('id'),
            'marketing_strategist' => Position::where('name', 'Marketing Strategist')->value('id'),
            'data_scientist' => Position::where('name', 'Data Scientist')->value('id'),
            'educator' => Position::where('name', 'Educator')->value('id'),
            'business_consultant' => Position::where('name', 'Business Consultant')->value('id'),
            'creative_director' => Position::where('name', 'Creative Director')->value('id'),
            'cloud_engineer' => Position::where('name', 'Cloud Engineer')->value('id'),
            'hr_professional' => Position::where('name', 'HR Professional')->value('id'),
        ];

        $industries = [
            'technology' => Industry::where('name', 'Technology')->value('id'),
            'software' => Industry::where('name', 'Software')->value('id'),
            'design' => Industry::where('name', 'Design')->value('id'),
            'business' => Industry::where('name', 'Business')->value('id'),
            'marketing' => Industry::where('name', 'Marketing')->value('id'),
            'advertising' => Industry::where('name', 'Advertising')->value('id'),
            'data' => Industry::where('name', 'Data')->value('id'),
            'education' => Industry::where('name', 'Education')->value('id'),
            'consulting' => Industry::where('name', 'Consulting')->value('id'),
            'human_resources' => Industry::where('name', 'Human Resources')->value('id'),
            'media' => Industry::where('name', 'Media')->value('id'),
            'cloud' => Industry::where('name', 'Cloud Computing')->value('id'),
        ];

        $users = [
            [
                'name' => 'Aarav Sharma',
                'email' => 'aarav@example.com',
                'username' => 'aaravsharma',
                'headline' => 'Software Engineer building scalable web applications and developer tools.',
                'position' => 'software_engineer',
                'country' => 'IN',
                'languages' => ['en', 'hi'],
                'industries' => ['technology', 'software'],
            ],

            [
                'name' => 'Priya Mehta',
                'email' => 'priya@example.com',
                'username' => 'priyamehta',
                'headline' => 'Product Designer focused on creating simple, accessible and meaningful digital experiences.',
                'position' => 'product_designer',
                'country' => 'IN',
                'languages' => ['en', 'hi', 'fr'],
                'industries' => ['design', 'technology'],
            ],

            [
                'name' => 'Daniel Williams',
                'email' => 'daniel@example.com',
                'username' => 'danielwilliams',
                'headline' => 'Technology entrepreneur helping businesses turn ideas into digital products.',
                'position' => 'founder',
                'country' => 'US',
                'languages' => ['en'],
                'industries' => ['technology', 'business'],
            ],

            [
                'name' => 'Sofia Martinez',
                'email' => 'sofia@example.com',
                'username' => 'sofiamartinez',
                'headline' => 'Marketing strategist helping ambitious brands build stronger digital identities.',
                'position' => 'marketing_strategist',
                'country' => 'ES',
                'languages' => ['en', 'es'],
                'industries' => ['marketing', 'advertising'],
            ],

            [
                'name' => 'Michael Chen',
                'email' => 'michael@example.com',
                'username' => 'michaelchen',
                'headline' => 'Data Scientist working at the intersection of machine learning, analytics and business.',
                'position' => 'data_scientist',
                'country' => 'CN',
                'languages' => ['en', 'zh'],
                'industries' => ['technology', 'data'],
            ],

            [
                'name' => 'Emma Johnson',
                'email' => 'emma@example.com',
                'username' => 'emmajohnson',
                'headline' => 'Educator and researcher passionate about making knowledge more accessible.',
                'position' => 'educator',
                'country' => 'GB',
                'languages' => ['en'],
                'industries' => ['education'],
            ],

            [
                'name' => 'Rahul Verma',
                'email' => 'rahul@example.com',
                'username' => 'rahulverma',
                'headline' => 'Full-stack developer creating modern applications with Laravel, Vue and TypeScript.',
                'position' => 'software_engineer',
                'country' => 'IN',
                'languages' => ['en', 'hi'],
                'industries' => ['technology', 'software'],
            ],

            [
                'name' => 'Olivia Brown',
                'email' => 'oliviabrown@example.com',
                'username' => 'oliviabrown',
                'headline' => 'Business consultant helping organizations improve strategy, operations and growth.',
                'position' => 'business_consultant',
                'country' => 'GB',
                'languages' => ['en'],
                'industries' => ['business', 'consulting'],
            ],

            [
                'name' => 'Arjun Kapoor',
                'email' => 'arjun@example.com',
                'username' => 'arjunkapoor',
                'headline' => 'Founder building products that connect technology, education and opportunity.',
                'position' => 'founder',
                'country' => 'IN',
                'languages' => ['en', 'hi'],
                'industries' => ['technology', 'education', 'business'],
            ],

            [
                'name' => 'Isabella Rossi',
                'email' => 'isabella@example.com',
                'username' => 'isabellarossi',
                'headline' => 'Creative director and brand strategist working with emerging global companies.',
                'position' => 'creative_director',
                'country' => 'IT',
                'languages' => ['en', 'it'],
                'industries' => ['design', 'media'],
            ],

            [
                'name' => 'Noah Wilson',
                'email' => 'noah@example.com',
                'username' => 'noahwilson',
                'headline' => 'Cloud engineer designing reliable infrastructure and developer platforms.',
                'position' => 'cloud_engineer',
                'country' => 'US',
                'languages' => ['en'],
                'industries' => ['technology', 'cloud'],
            ],

            [
                'name' => 'Ananya Singh',
                'email' => 'ananya@example.com',
                'username' => 'ananyasingh',
                'headline' => 'HR professional focused on people, culture and building better workplaces.',
                'position' => 'hr_professional',
                'country' => 'IN',
                'languages' => ['en', 'hi'],
                'industries' => ['human_resources', 'business'],
            ],
        ];

        foreach ($users as $userData) {
            $user = User::updateOrCreate(
                [
                    'email' => $userData['email'],
                ],
                [
                    'name' => $userData['name'],
                    'username' => $userData['username'],
                    'headline' => $userData['headline'],
                    'position_id' => $positions[$userData['position']] ?? null,
                    'country_id' => $countries[$userData['country']] ?? null,
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'is_active' => true,
                ],
            );

            $languageIds = collect($userData['languages'])
                ->map(fn (string $code) => $languages[$code] ?? null)
                ->filter()
                ->values()
                ->all();

            $industryIds = collect($userData['industries'])
                ->map(fn (string $name) => $industries[$name] ?? null)
                ->filter()
                ->values()
                ->all();

            $user->languages()->sync($languageIds);
            $user->industries()->sync($industryIds);
        }
    }
}