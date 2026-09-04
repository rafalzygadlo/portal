<?php

namespace App\Policies\Concerns;

use App\Models\Company;
use App\Models\User;

trait ChecksCompanyOwnership
{
    protected function userBelongsToCompany(User $user, ?Company $company): bool
    {
        if (!$company) {
            return false;
        }

        if ($this->userOwnsCompany($user, $company)) {
            return true;
        }

        if ($company->relationLoaded('users')) {
            return $company->users->contains(fn (User $member) => (int) $member->id === (int) $user->id);
        }

        return $company->users()->whereKey($user->id)->exists();
    }

    protected function userOwnsCompany(User $user, ?Company $company): bool
    {
        if (!$company) {
            return false;
        }

        if ((int) $company->user_id === (int) $user->id) {
            return true;
        }

        if ($company->relationLoaded('owners')) {
            return $company->owners->contains(fn (User $owner) => (int) $owner->id === (int) $user->id);
        }

        return $company->owners()->whereKey($user->id)->exists();
    }
}
