<?php

namespace App\Actions\Research;

use App\Models\Research\Research;

class UpdateResearch
{
    public function handle(
        Research $research,
        array $data,
    ): Research {
        $research->update($data);

        return $research->fresh();
    }
}
