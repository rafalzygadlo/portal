<?php

namespace App\Policies;

use App\Models\Poll\Poll;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PollPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Poll\Poll  $poll
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Poll $poll)
    {
        return $user->id === $poll->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Poll\Poll  $poll
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Poll $poll)
    {
        return $user->id === $poll->user_id;
    }
}
