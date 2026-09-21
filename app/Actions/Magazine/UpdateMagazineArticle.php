<?php

namespace App\Actions\Magazine;

use App\Models\Magazine\MagazineArticle;
use Illuminate\Support\Facades\DB;

class UpdateMagazineArticle
{
    public function handle(
        MagazineArticle $article,
        array $data,
    ): MagazineArticle {
        return DB::transaction(function () use (
            $article,
            $data,
        ) {
            $article->update($data);

            return $article->refresh();
        });
    }
}