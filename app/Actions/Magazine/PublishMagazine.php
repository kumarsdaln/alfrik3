<?php

namespace App\Actions\Magazine;

use App\Enums\Magazine\MagazineStatus;
use App\Models\Magazine\Magazine;
use Illuminate\Support\Facades\DB;

class PublishMagazine
{
    public function handle(Magazine $magazine): Magazine
    {
        return DB::transaction(function () use ($magazine) {
            $magazine->update([
                'status' => MagazineStatus::Published,
                'published_at' => now(),
            ]);

            return $magazine->refresh();
        });
    }
}