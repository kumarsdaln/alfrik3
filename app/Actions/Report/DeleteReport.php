<?php

namespace App\Actions\Report;

use App\Models\Report\Report;

class DeleteReport
{
    public function handle(Report $report): void
    {
        $report->delete();
    }
}