<?php

namespace App\Actions\Magazine;

use App\Models\Magazine\MagazineArticle;
use Illuminate\Support\Facades\DB;

class DeleteMagazineArticle
{
    public function handle(MagazineArticle $article): void
    {
        DB::transaction(function () use ($article) {
            $article->delete();
        });
    }
}