<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ContinentSeeder::class,
            CountrySeeder::class,
            LanguageSeeder::class,
            IndustrySeeder::class,
            PositionSeeder::class,
            RolePermissionSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            TagSeeder::class,
            ParticipantRoleSeeder::class,

            // Content Seeders
            InterviewSeeder::class,
            ResearchSeeder::class,

            // Magazine Seeders
            MagazineSeeder::class,
            MagazineIssueSeeder::class,
            MagazineArticleSeeder::class,

            // Research Seeders
            ReportSeeder::class,
            ReportSectionSeeder::class,
            ReportContentBlockSeeder::class,
        ]);
    }
}
