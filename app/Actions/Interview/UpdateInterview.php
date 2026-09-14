<?php

namespace App\Actions\Interview;

use App\Models\Interview\Interview;
use Illuminate\Support\Facades\DB;

class UpdateInterview
{
    public function handle(
        Interview $interview,
        array $data,
    ): Interview {
        return DB::transaction(function () use ($interview, $data) {
            $interview->update($data);

            return $interview->refresh();
        });
    }
}