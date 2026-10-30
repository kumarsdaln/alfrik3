<?php

namespace App\Policies;

use App\Models\Event\Event;
use App\Models\User;

class EventPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin()
            || $user->hasAnyPermission(['events.index']);
    }

    public function view(User $user, Event $event): bool
    {
        return $user->isSuperAdmin()
            || $user->hasAnyPermission(['events.view'])
            || $event->created_by === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isSuperAdmin()
            || $user->hasAnyPermission(['events.create']);
    }

    public function update(User $user, Event $event): bool
    {
        return $user->isSuperAdmin()
            || $user->hasAnyPermission(['events.update'])
            || $event->created_by === $user->id;
    }

    public function delete(User $user, Event $event): bool
    {
        return $user->isSuperAdmin()
            || $user->hasAnyPermission(['events.delete'])
            || $event->created_by === $user->id;
    }

    public function publish(User $user, Event $event): bool
    {
        return $user->isSuperAdmin()
            || $user->hasAnyPermission(['events.publish']);
    }
}
