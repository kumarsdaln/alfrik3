<?php

namespace App\Actions\Magazine;

use App\Enums\Magazine\MagazineArticleStatus;
use App\Models\Magazine\MagazineArticle;
use Illuminate\Support\Facades\DB;

class PublishMagazineArticle
{
    public function handle(
        MagazineArticle $article,
    ): MagazineArticle {
        return DB::transaction(function () use ($article) {
            $article->update([
                'status' => MagazineArticleStatus::Published,
                'published_at' => now(),
            ]);

            return $article->refresh();
        });
    }
}