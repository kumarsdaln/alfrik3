<?php

namespace App\Actions\Magazine;

use App\Models\Magazine\MagazineArticle;
use App\Models\Magazine\MagazineIssue;
use Illuminate\Support\Facades\DB;

class CreateMagazineArticle
{
    public function handle(
        MagazineIssue $issue,
        array $data,
    ): MagazineArticle {
        return DB::transaction(function () use ($issue, $data) {
            $data['issue_id'] = $issue->id;

            return MagazineArticle::create($data);
        });
    }
}