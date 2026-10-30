<?php

namespace App\Actions\Event;

use App\Models\Event\Event;
use Illuminate\Support\Facades\DB;

class DeleteEvent
{
    public function handle(Event $event): void
    {
        DB::transaction(function () use ($event) {
            $event->delete();
        });
    }
}
