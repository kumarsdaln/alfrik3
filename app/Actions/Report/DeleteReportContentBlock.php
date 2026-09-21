<?php

namespace App\Actions\Report;

use App\Models\Report\ReportContentBlock;

class DeleteReportContentBlock
{
    public function handle(
        ReportContentBlock $block,
    ): void {
        $block->delete();
    }
}