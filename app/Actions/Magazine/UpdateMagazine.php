<?php

namespace App\Actions\Magazine;

use App\Models\Magazine\Magazine;
use Illuminate\Support\Facades\DB;

class UpdateMagazine
{
    public function handle(
        Magazine $magazine,
        array $data,
    ): Magazine {
        return DB::transaction(function () use (
            $magazine,
            $data,
        ) {
            $magazine->update($data);

            return $magazine->refresh();
        });
    }
}
