<?php

namespace App\Policies\Concerns;

use App\Models\Business;
use App\Models\User;

trait ChecksBusinessOwnership
{
    protected function userOwnsBusiness(User $user, ?Business $business): bool
    {
        if (!$business) {
            return false;
        }

        if ($business->relationLoaded('owners')) {
            return $business->owners->contains(fn (User $owner) => (int) $owner->id === (int) $user->id);
        }

        return $business->owners()->whereKey($user->id)->exists();
    }
}
