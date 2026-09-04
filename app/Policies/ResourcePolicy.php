<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\Resource;
use App\Models\User;
use App\Policies\Concerns\ChecksCompanyOwnership;

class ResourcePolicy
{
    use ChecksCompanyOwnership;

    public function update(User $user, Resource $resource): bool
    {
        return $this->isResourceOwner($user, $resource)
            || $this->userOwnsCompany($user, $this->resolveCompany($resource));
    }

    public function delete(User $user, Resource $resource): bool
    {
        return $this->isResourceOwner($user, $resource)
            || $this->userOwnsCompany($user, $this->resolveCompany($resource));
    }

    private function isResourceOwner(User $user, Resource $resource): bool
    {
        return !empty($resource->user_id) && (int) $user->id === (int) $resource->user_id;
    }

    private function resolveCompany(Resource $resource): ?Company
    {
        if ($resource->relationLoaded('company')) {
            return $resource->getRelation('company');
        }

        if (!$resource->company_id) {
            return null;
        }

        return $resource->company;
    }
}
