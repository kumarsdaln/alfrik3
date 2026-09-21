<?php

namespace App\Actions\Report;

use App\Models\Report\Report;
use App\Models\Report\ReportSection;

class CreateReportSection
{
    public function handle(
        Report $report,
        array $data,
    ): ReportSection {
        return $report->sections()->create($data);
    }
}