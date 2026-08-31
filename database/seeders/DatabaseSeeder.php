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
            InterviewSeeder::class,
            ReportCategorySeeder::class,
            ReportSeeder::class,
            ResearchAreaSeeder::class,
            ResearchPaperSeeder::class,
            SurveySeeder::class,
            MagazineSeeder::class,
            EventCategorySeeder::class,
            EventSeeder::class,
        ]);
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
