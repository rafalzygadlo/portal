<?php

namespace App\Policies;

use App\Models\Notification;
use App\Models\User;

class NotificationPolicy
{
    public function view(User $user, Notification $notification): bool
    {
        return (int) $user->id === (int) $notification->user_id;
    }

    public function update(User $user, Notification $notification): bool
    {
        return (int) $user->id === (int) $notification->user_id;
    }

    public function delete(User $user, Notification $notification): bool
    {
        return (int) $user->id === (int) $notification->user_id;
    }
}
