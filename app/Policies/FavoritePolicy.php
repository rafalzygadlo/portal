<?php

namespace App\Policies;

use App\Models\Favorite;
use App\Models\User;

class FavoritePolicy
{
    public function update(User $user, Favorite $favorite): bool
    {
        return (int) $user->id === (int) $favorite->user_id;
    }

    public function delete(User $user, Favorite $favorite): bool
    {
        return (int) $user->id === (int) $favorite->user_id;
    }
}
