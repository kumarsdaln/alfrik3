<?php

namespace App\Actions\Magazine;

use App\Models\Magazine\Magazine;
use Illuminate\Support\Facades\DB;

class CreateMagazine
{
    public function handle(array $data): Magazine
    {
        return DB::transaction(function () use ($data) {
            return Magazine::create($data);
        });
    }
}