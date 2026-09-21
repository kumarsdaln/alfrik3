<?php

namespace App\Actions\Report;

use App\Models\Report\Report;

class UpdateReport
{
    public function handle(
        Report $report,
        array $data,
    ): Report {
        $report->update($data);

        return $report->refresh();
    }
}