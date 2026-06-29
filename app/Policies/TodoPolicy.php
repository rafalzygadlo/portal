<?php

namespace App\Policies;

use App\Models\Todo;
use App\Models\User;

class TodoPolicy
{
    public function update(User $user, Todo $todo): bool
    {
        return (int) $user->id === (int) $todo->user_id;
    }

    public function delete(User $user, Todo $todo): bool
    {
        return (int) $user->id === (int) $todo->user_id;
    }
}
