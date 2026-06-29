<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function view(User $user, User $targetUser): bool
    {
        return $this->isSameUser($user, $targetUser) || $this->isAdmin($user);
    }

    public function update(User $user, User $targetUser): bool
    {
        return $this->isSameUser($user, $targetUser) || $this->isAdmin($user);
    }

    public function delete(User $user, User $targetUser): bool
    {
        return $this->isSameUser($user, $targetUser) || $this->isAdmin($user);
    }

    private function isSameUser(User $user, User $targetUser): bool
    {
        return (int) $user->id === (int) $targetUser->id;
    }

    private function isAdmin(User $user): bool
    {
        return (string) ($user->user_type ?? '') === 'admin';
    }
}
