<?php

namespace App\Policies;

use App\Models\BookingFlow;
use App\Models\Company;
use App\Models\User;
use App\Policies\Concerns\ChecksCompanyOwnership;

class BookingFlowPolicy
{
    use ChecksCompanyOwnership;

    public function update(User $user, BookingFlow $bookingFlow): bool
    {
        return $this->userOwnsCompany($user, $this->resolveCompany($bookingFlow));
    }

    public function delete(User $user, BookingFlow $bookingFlow): bool
    {
        return $this->userOwnsCompany($user, $this->resolveCompany($bookingFlow));
    }

    private function resolveCompany(BookingFlow $bookingFlow): ?Company
    {
        if ($bookingFlow->relationLoaded('company')) {
            return $bookingFlow->getRelation('company');
        }

        if (!$bookingFlow->company_id) {
            return null;
        }

        return $bookingFlow->company;
    }
}
