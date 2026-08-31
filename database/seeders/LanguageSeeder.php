<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        $languages = [
            ['code' => 'ar', 'name' => 'Arabic', 'native' => 'العربية', 'rtl' => true],
            ['code' => 'bn', 'name' => 'Bengali', 'native' => 'বাংলা', 'rtl' => false],
            ['code' => 'de', 'name' => 'German', 'native' => 'Deutsch', 'rtl' => false],
            ['code' => 'en', 'name' => 'English', 'native' => 'English', 'rtl' => false],
            ['code' => 'es', 'name' => 'Spanish', 'native' => 'Español', 'rtl' => false],
            ['code' => 'fa', 'name' => 'Persian', 'native' => 'فارسی', 'rtl' => true],
            ['code' => 'fr', 'name' => 'French', 'native' => 'Français', 'rtl' => false],
            ['code' => 'gu', 'name' => 'Gujarati', 'native' => 'ગુજરાતી', 'rtl' => false],
            ['code' => 'hi', 'name' => 'Hindi', 'native' => 'हिन्दी', 'rtl' => false],
            ['code' => 'id', 'name' => 'Indonesian', 'native' => 'Bahasa Indonesia', 'rtl' => false],
            ['code' => 'it', 'name' => 'Italian', 'native' => 'Italiano', 'rtl' => false],
            ['code' => 'ja', 'name' => 'Japanese', 'native' => '日本語', 'rtl' => false],
            ['code' => 'ko', 'name' => 'Korean', 'native' => '한국어', 'rtl' => false],
            ['code' => 'mr', 'name' => 'Marathi', 'native' => 'मराठी', 'rtl' => false],
            ['code' => 'ms', 'name' => 'Malay', 'native' => 'Bahasa Melayu', 'rtl' => false],
            ['code' => 'ne', 'name' => 'Nepali', 'native' => 'नेपाली', 'rtl' => false],
            ['code' => 'nl', 'name' => 'Dutch', 'native' => 'Nederlands', 'rtl' => false],
            ['code' => 'pa', 'name' => 'Punjabi', 'native' => 'ਪੰਜਾਬੀ', 'rtl' => false],
            ['code' => 'pl', 'name' => 'Polish', 'native' => 'Polski', 'rtl' => false],
            ['code' => 'pt', 'name' => 'Portuguese', 'native' => 'Português', 'rtl' => false],
            ['code' => 'ru', 'name' => 'Russian', 'native' => 'Русский', 'rtl' => false],
            ['code' => 'ta', 'name' => 'Tamil', 'native' => 'தமிழ்', 'rtl' => false],
            ['code' => 'te', 'name' => 'Telugu', 'native' => 'తెలుగు', 'rtl' => false],
            ['code' => 'th', 'name' => 'Thai', 'native' => 'ไทย', 'rtl' => false],
            ['code' => 'tr', 'name' => 'Turkish', 'native' => 'Türkçe', 'rtl' => false],
            ['code' => 'uk', 'name' => 'Ukrainian', 'native' => 'Українська', 'rtl' => false],
            ['code' => 'ur', 'name' => 'Urdu', 'native' => 'اردو', 'rtl' => true],
            ['code' => 'vi', 'name' => 'Vietnamese', 'native' => 'Tiếng Việt', 'rtl' => false],
            ['code' => 'zh', 'name' => 'Chinese', 'native' => '中文', 'rtl' => false],
        ];

        DB::table('languages')->upsert(
            $languages,
            ['code'],
            ['name', 'native', 'rtl'],
        );
    }
}