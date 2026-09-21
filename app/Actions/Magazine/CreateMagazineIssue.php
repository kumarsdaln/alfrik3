<?php

namespace App\Actions\Magazine;

use App\Models\Magazine\MagazineIssue;
use Illuminate\Support\Facades\DB;

class CreateMagazineIssue
{
    public function handle(array $data): MagazineIssue
    {
        return DB::transaction(function () use ($data) {
            return MagazineIssue::create($data);
        });
    }
}