<?php

namespace App\Actions\Magazine;

use App\Enums\Magazine\MagazineIssueStatus;
use App\Models\Magazine\MagazineIssue;

class ArchiveMagazineIssue
{
    public function handle(MagazineIssue $issue): MagazineIssue
    {
        $issue->update([
            'status' => MagazineIssueStatus::Archived,
        ]);

        return $issue;
    }
}