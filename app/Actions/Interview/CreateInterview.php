<?php

namespace App\Actions\Interview;

use App\Models\Interview\Interview;
use Illuminate\Support\Facades\DB;

class CreateInterview
{
    public function handle(array $data): Interview
    {
        return DB::transaction(function () use ($data) {
            return Interview::create($data);
        });
    }
}