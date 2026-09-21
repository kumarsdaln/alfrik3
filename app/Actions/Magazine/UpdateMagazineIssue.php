<?php

namespace App\Actions\Magazine;

use App\Models\Magazine\MagazineIssue;
use Illuminate\Support\Facades\DB;

class UpdateMagazineIssue
{
    public function handle(
        MagazineIssue $issue,
        array $data,
    ): MagazineIssue {
        return DB::transaction(function () use (
            $issue,
            $data,
        ) {
            $issue->update($data);

            return $issue->refresh();
        });
    }
}