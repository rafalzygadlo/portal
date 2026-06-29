<?php

namespace App\Policies;

use App\Models\Announcement;
use App\Models\User;

class AnnouncementPolicy
{
    public function update(User $user, Announcement $announcement): bool
    {
        return (int) $user->id === (int) $announcement->user_id;
    }

    public function delete(User $user, Announcement $announcement): bool
    {
        return (int) $user->id === (int) $announcement->user_id;
    }
}
