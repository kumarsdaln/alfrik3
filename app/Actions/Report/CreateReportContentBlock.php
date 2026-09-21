<?php

namespace App\Actions\Report;

use App\Models\Report\ReportContentBlock;
use App\Models\Report\ReportSection;

class CreateReportContentBlock
{
    public function handle(
        ReportSection $section,
        array $data,
    ): ReportContentBlock {
        return $section->contentBlocks()->create($data);
    }
}