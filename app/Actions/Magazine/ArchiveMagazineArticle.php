<?php

namespace App\Actions\Magazine;

use App\Enums\Magazine\MagazineArticleStatus;
use App\Models\Magazine\MagazineArticle;
use Illuminate\Support\Facades\DB;

class ArchiveMagazineArticle
{
    public function handle(
        MagazineArticle $article,
    ): MagazineArticle {
        return DB::transaction(function () use ($article) {
            $article->update([
                'status' => MagazineArticleStatus::Archived,
            ]);

            return $article->refresh();
        });
    }
}