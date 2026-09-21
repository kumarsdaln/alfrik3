<?php

namespace App\Actions\Magazine;

use App\Enums\Magazine\MagazineStatus;
use App\Models\Magazine\Magazine;
use Illuminate\Support\Facades\DB;

class ArchiveMagazine
{
    public function handle(Magazine $magazine): Magazine
    {
        return DB::transaction(function () use ($magazine) {
            $magazine->update([
                'status' => MagazineStatus::Archived,
            ]);

            return $magazine->refresh();
        });
    }
}