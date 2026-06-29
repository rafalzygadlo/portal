<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    public function update(User $user, Category $category): bool
    {
        return $this->isAdmin($user);
    }

    public function delete(User $user, Category $category): bool
    {
        return $this->isAdmin($user);
    }

    private function isAdmin(User $user): bool
    {
        return (string) ($user->user_type ?? '') === 'admin';
    }
}
