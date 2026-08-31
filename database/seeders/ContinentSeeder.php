<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContinentSeeder extends Seeder
{
    public function run(): void
    {
        $continents = [
            [
                'code' => 'AF',
                'name' => 'Africa',
            ],
            [
                'code' => 'AS',
                'name' => 'Asia',
            ],
            [
                'code' => 'EU',
                'name' => 'Europe',
            ],
            [
                'code' => 'NA',
                'name' => 'North America',
            ],
            [
                'code' => 'SA',
                'name' => 'South America',
            ],
            [
                'code' => 'OC',
                'name' => 'Oceania',
            ],
            [
                'code' => 'AN',
                'name' => 'Antarctica',
            ],
        ];

        DB::table('continents')->upsert(
            $continents,
            ['code'],
            ['name'],
        );
    }
}