<?php

namespace App\Policies;

use App\Models\Business;
use App\Models\Service;
use App\Models\User;
use App\Policies\Concerns\ChecksBusinessOwnership;

class ServicePolicy
{
    use ChecksBusinessOwnership;

    public function update(User $user, Service $service): bool
    {
        return $this->userOwnsBusiness($user, $this->resolveBusiness($service));
    }

    public function delete(User $user, Service $service): bool
    {
        return $this->userOwnsBusiness($user, $this->resolveBusiness($service));
    }

    private function resolveBusiness(Service $service): ?Business
    {
        if ($service->relationLoaded('business')) {
            return $service->getRelation('business');
        }

        if (!$service->business_id) {
            return null;
        }

        return $service->business;
    }
}
