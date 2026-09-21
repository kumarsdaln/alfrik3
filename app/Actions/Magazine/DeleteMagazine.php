<?php

namespace App\Actions\Magazine;

use App\Models\Magazine\Magazine;
use Illuminate\Support\Facades\DB;

class DeleteMagazine
{
    public function handle(Magazine $magazine): void
    {
        DB::transaction(function () use ($magazine) {
            $magazine->delete();
        });
    }
}