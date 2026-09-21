<?php

namespace App\Actions\Report;

use App\Models\Report\Report;

class CreateReport
{
    public function handle(array $data): Report
    {
        return Report::create($data);
    }
}