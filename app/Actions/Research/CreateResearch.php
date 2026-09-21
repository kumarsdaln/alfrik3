<?php

namespace App\Actions\Research;

use App\Models\Research\Research;

class CreateResearch
{
    public function handle(array $data): Research
    {
        return Research::create($data);
    }
}