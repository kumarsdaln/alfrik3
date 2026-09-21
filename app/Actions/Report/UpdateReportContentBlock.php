<?php

namespace App\Actions\Report;

use App\Models\Report\ReportContentBlock;

class UpdateReportContentBlock
{
    public function handle(
        ReportContentBlock $block,
        array $data,
    ): ReportContentBlock {
        $block->update($data);

        return $block->refresh();
    }
}
