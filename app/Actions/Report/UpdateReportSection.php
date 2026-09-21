<?php

namespace App\Actions\Report;

use App\Models\Report\ReportSection;

class UpdateReportSection
{
    public function handle(
        ReportSection $section,
        array $data,
    ): ReportSection {
        $section->update($data);

        return $section->refresh();
    }
}