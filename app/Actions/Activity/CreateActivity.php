<?php

namespace App\Actions\Activity;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class CreateActivity
{
    public function handle(
        string $action,
        Model $subject,
        ?User $user = null,
        array $properties = [],
    ): Activity {
        return Activity::create([
            'user_id' => $user?->id,
            'action' => $action,
            'subject_type' => $subject->getMorphClass(),
            'subject_id' => $subject->getKey(),
            'properties' => $properties,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
