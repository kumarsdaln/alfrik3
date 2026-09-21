<?php

namespace App\Actions\Magazine;

use App\Enums\Magazine\MagazineIssueStatus;
use App\Models\Magazine\MagazineIssue;
use Illuminate\Support\Facades\DB;

class PublishMagazineIssue
{
    public function handle(MagazineIssue $issue): MagazineIssue
    {
        return DB::transaction(function () use ($issue) {
            $issue->update([
                'status' => MagazineIssueStatus::Published,
                'published_at' => now(),
            ]);

            return $issue->refresh();
        });
    }
}