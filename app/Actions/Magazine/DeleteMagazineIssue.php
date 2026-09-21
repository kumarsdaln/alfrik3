<?php

namespace App\Actions\Magazine;

use App\Models\Magazine\MagazineIssue;
use Illuminate\Support\Facades\DB;

class DeleteMagazineIssue
{
    public function handle(MagazineIssue $issue): void
    {
        DB::transaction(function () use ($issue) {
            $issue->delete();
        });
    }
}