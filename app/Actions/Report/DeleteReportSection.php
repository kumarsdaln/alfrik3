<?php

namespace App\Actions\Report;

use App\Models\Report\ReportSection;

class DeleteReportSection
{
    public function handle(
        ReportSection $section,
    ): void {
        $section->delete();
    }
}