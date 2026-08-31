<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IndustrySeeder extends Seeder
{
    public function run(): void
    {
        $industries = [
            'Agriculture',
            'Architecture',
            'Arts & Culture',
            'Automotive',
            'Banking & Finance',
            'Biotechnology',
            'Business & Management',
            'Construction',
            'Consulting',
            'Education',
            'Energy',
            'Entertainment',
            'Environment',
            'Fashion',
            'Food & Beverage',
            'Government & Public Policy',
            'Healthcare',
            'Hospitality & Tourism',
            'Information Technology',
            'Insurance',
            'Journalism & Media',
            'Law',
            'Manufacturing',
            'Marketing & Advertising',
            'Media & Publishing',
            'Nonprofit & Social Impact',
            'Pharmaceuticals',
            'Real Estate',
            'Research & Science',
            'Retail & E-commerce',
            'Sports',
            'Telecommunications',
            'Transportation & Logistics',
            'Venture Capital & Private Equity',
        ];

        DB::table('industries')->upsert(
            collect($industries)
                ->map(fn (string $name) => [
                    'name' => $name,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
                ->all(),
            ['name'],
            ['updated_at'],
        );
    }
}