<?php

namespace App\Actions\Interview;

use App\Models\Interview\Interview;
use Illuminate\Support\Facades\DB;

class DeleteInterview
{
    public function handle(Interview $interview): void
    {
        DB::transaction(function () use ($interview) {
            $interview->delete();
        });
    }
}