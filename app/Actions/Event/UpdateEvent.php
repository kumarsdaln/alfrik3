<?php

namespace App\Actions\Event;

use App\Models\Event\Event;
use Illuminate\Support\Facades\DB;

class UpdateEvent
{
    public function handle(
        Event $event,
        array $data,
    ): Event {
        return DB::transaction(function () use ($event, $data) {
            $event->update($data);

            return $event->refresh();
        });
    }
}
