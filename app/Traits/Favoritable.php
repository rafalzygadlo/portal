<?php

namespace App\Traits;

use App\Models\Favorite;

trait Favoritable
{
    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    public function isFavoritedBy($user = null)
    {
        $userId = null;

        if ($user instanceof \App\Models\User) {
            $userId = $user->id;
        } elseif (is_int($user)) {
            $userId = $user;
        }

        $userId = $userId ?: auth()->id();

        return $userId ? $this->favorites()->where('user_id', $userId)->exists() : false;
    }

    public function getFavoritesCountAttribute()
    {
        return $this->favorites()->count();
    }
}
