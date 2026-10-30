<?php

namespace App\Actions\Magazine;

use App\Models\Magazine\Magazine;
use App\Models\Magazine\MagazineIssue;
use Illuminate\Support\Facades\DB;

class CreateMagazineIssue
{
    public function handle(
        Magazine $magazine,
        array $data,
    ): MagazineIssue {
        return DB::transaction(function () use ($magazine, $data) {
            $data['magazine_id'] = $magazine->id;

            return MagazineIssue::create($data);
        });
    }
}