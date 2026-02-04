<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BasePolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @param mixed $ability
     * @return bool
     */
    public function before(User $user, mixed $ability): bool
    {
        return true;
        return $user->is_admin;
    }
}
