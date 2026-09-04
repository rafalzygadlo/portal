<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\Service;
use App\Models\User;
use App\Policies\Concerns\ChecksCompanyOwnership;

class ServicePolicy
{
    use ChecksCompanyOwnership;

    public function update(User $user, Service $service): bool
    {
        return $this->userOwnsCompany($user, $this->resolveCompany($service));
    }

    public function delete(User $user, Service $service): bool
    {
        return $this->userOwnsCompany($user, $this->resolveCompany($service));
    }

    private function resolveCompany(Service $service): ?Company
    {
        if ($service->relationLoaded('company')) {
            return $service->getRelation('company');
        }

        if (!$service->company_id) {
            return null;
        }

        return $service->company;
    }
}
