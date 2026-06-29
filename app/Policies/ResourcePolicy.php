<?php

namespace App\Policies;

use App\Models\Business;
use App\Models\Resource;
use App\Models\User;
use App\Policies\Concerns\ChecksBusinessOwnership;

class ResourcePolicy
{
    use ChecksBusinessOwnership;

    public function update(User $user, Resource $resource): bool
    {
        return $this->isResourceOwner($user, $resource)
            || $this->userOwnsBusiness($user, $this->resolveBusiness($resource));
    }

    public function delete(User $user, Resource $resource): bool
    {
        return $this->isResourceOwner($user, $resource)
            || $this->userOwnsBusiness($user, $this->resolveBusiness($resource));
    }

    private function isResourceOwner(User $user, Resource $resource): bool
    {
        return !empty($resource->user_id) && (int) $user->id === (int) $resource->user_id;
    }

    private function resolveBusiness(Resource $resource): ?Business
    {
        if ($resource->relationLoaded('business')) {
            return $resource->getRelation('business');
        }

        if (!$resource->business_id) {
            return null;
        }

        return $resource->business;
    }
}
