<?php

namespace App\Actions\Magazine;

use App\Models\Magazine\MagazineArticle;
use Illuminate\Support\Facades\DB;

class CreateMagazineArticle
{
    public function handle(array $data): MagazineArticle
    {
        return DB::transaction(function () use ($data) {
            return MagazineArticle::create($data);
        });
    }
}