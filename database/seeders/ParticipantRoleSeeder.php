<?php

namespace Database\Seeders;

use App\Models\ParticipantRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ParticipantRoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Author',
                'description' => 'Person who writes or creates the content.',
                'position' => 1,
            ],
            [
                'name' => 'Co-Author',
                'description' => 'Person who collaborates in writing or creating the content.',
                'position' => 2,
            ],
            [
                'name' => 'Editor',
                'description' => 'Person responsible for reviewing and editing the content.',
                'position' => 3,
            ],
            [
                'name' => 'Contributor',
                'description' => 'Person who contributes information, insights, or material.',
                'position' => 4,
            ],
            [
                'name' => 'Interviewer',
                'description' => 'Person who conducts an interview.',
                'position' => 5,
            ],
            [
                'name' => 'Interviewee',
                'description' => 'Person who participates in an interview.',
                'position' => 6,
            ],
            [
                'name' => 'Photographer',
                'description' => 'Person responsible for capturing photographs.',
                'position' => 7,
            ],
            [
                'name' => 'Videographer',
                'description' => 'Person responsible for recording video content.',
                'position' => 8,
            ],
            [
                'name' => 'Researcher',
                'description' => 'Person responsible for conducting research and analysis.',
                'position' => 9,
            ],
            [
                'name' => 'Subject Matter Expert',
                'description' => 'Expert who provides specialized knowledge and insights.',
                'position' => 10,
            ],
            [
                'name' => 'Designer',
                'description' => 'Person responsible for visual design and creative direction.',
                'position' => 11,
            ],
            [
                'name' => 'Publisher',
                'description' => 'Organization or person responsible for publishing the content.',
                'position' => 12,
            ],
        ];

        foreach ($roles as $role) {
            ParticipantRole::updateOrCreate(
                ['slug' => Str::slug($role['name'])],
                [
                    'name' => $role['name'],
                    'description' => $role['description'],
                    'is_active' => true,
                    'position' => $role['position'],
                ]
            );
        }
    }
}
