<?php

namespace App\Policies;

use App\Models\Poll\Poll;
use App\Models\Poll\PollOption;
use App\Models\User;

class PollOptionPolicy
{
    public function update(User $user, PollOption $pollOption): bool
    {
        return $this->ownsPoll($user, $pollOption);
    }

    public function delete(User $user, PollOption $pollOption): bool
    {
        return $this->ownsPoll($user, $pollOption);
    }

    private function ownsPoll(User $user, PollOption $pollOption): bool
    {
        $poll = $pollOption->relationLoaded('poll') ? $pollOption->getRelation('poll') : $pollOption->poll;

        if (!$poll instanceof Poll) {
            return false;
        }

        return (int) $user->id === (int) $poll->user_id;
    }
}
