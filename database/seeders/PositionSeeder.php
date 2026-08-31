<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PositionSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = [
            'Founder',
            'Co-Founder',
            'CEO',
            'CTO',
            'Director',
            'Manager',
            'Project Manager',
            'Product Manager',

            'Software Engineer',
            'Senior Software Engineer',
            'Software Developer',
            'Web Developer',
            'Mobile App Developer',
            'Data Scientist',
            'Data Analyst',
            'DevOps Engineer',
            'Cybersecurity Specialist',

            'UI/UX Designer',
            'Graphic Designer',
            'Creative Director',

            'Journalist',
            'Editor',
            'Writer',
            'Content Creator',
            'Photographer',
            'Filmmaker',

            'Researcher',
            'Scientist',
            'Professor',
            'Teacher',
            'Consultant',

            'Lawyer',
            'Doctor',
            'Accountant',
            'Financial Analyst',
            'Marketing Specialist',
            'Sales Manager',
            'Human Resources Manager',

            'Entrepreneur',
            'Freelancer',
            'Student',
            'Research Student',
            'Other',
        ];

        foreach ($positions as $name) {
            Position::updateOrCreate(
                ['name' => $name],
                ['slug' => Str::slug($name)],
            );
        }
    }
}
