<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vote;

class VotePolicy
{
    public function update(User $user, Vote $vote): bool
    {
        return (int) $user->id === (int) $vote->user_id;
    }

    public function delete(User $user, Vote $vote): bool
    {
        return (int) $user->id === (int) $vote->user_id;
    }
}
