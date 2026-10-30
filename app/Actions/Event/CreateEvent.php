<?php

namespace App\Actions\Event;

use App\Models\Event\Event;
use Illuminate\Support\Facades\DB;

class CreateEvent
{
    public function handle(array $data): Event
    {
        return DB::transaction(function () use ($data) {
            return Event::create($data);
        });
    }
}
