<?php

namespace App\Policies;

use App\Models\Business;
use App\Models\Reservation;
use App\Models\User;
use App\Policies\Concerns\ChecksBusinessOwnership;

class ReservationPolicy
{
    use ChecksBusinessOwnership;

    public function view(User $user, Reservation $reservation): bool
    {
        return $this->isReservationOwner($user, $reservation)
            || $this->userOwnsBusiness($user, $this->resolveBusiness($reservation));
    }

    public function update(User $user, Reservation $reservation): bool
    {
        return $this->isReservationOwner($user, $reservation)
            || $this->userOwnsBusiness($user, $this->resolveBusiness($reservation));
    }

    public function delete(User $user, Reservation $reservation): bool
    {
        return $this->isReservationOwner($user, $reservation)
            || $this->userOwnsBusiness($user, $this->resolveBusiness($reservation));
    }

    private function isReservationOwner(User $user, Reservation $reservation): bool
    {
        return (int) $user->id === (int) $reservation->user_id;
    }

    private function resolveBusiness(Reservation $reservation): ?Business
    {
        if ($reservation->relationLoaded('business')) {
            return $reservation->getRelation('business');
        }

        if (!$reservation->business_id) {
            return null;
        }

        return $reservation->business;
    }
}
