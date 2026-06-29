<?php

namespace App\Policies;

use App\Models\BookingFlow;
use App\Models\Business;
use App\Models\User;
use App\Policies\Concerns\ChecksBusinessOwnership;

class BookingFlowPolicy
{
    use ChecksBusinessOwnership;

    public function update(User $user, BookingFlow $bookingFlow): bool
    {
        return $this->userOwnsBusiness($user, $this->resolveBusiness($bookingFlow));
    }

    public function delete(User $user, BookingFlow $bookingFlow): bool
    {
        return $this->userOwnsBusiness($user, $this->resolveBusiness($bookingFlow));
    }

    private function resolveBusiness(BookingFlow $bookingFlow): ?Business
    {
        if ($bookingFlow->relationLoaded('business')) {
            return $bookingFlow->getRelation('business');
        }

        if (!$bookingFlow->business_id) {
            return null;
        }

        return $bookingFlow->business;
    }
}
