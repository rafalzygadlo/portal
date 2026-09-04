<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\Reservation;
use App\Models\User;
use App\Policies\Concerns\ChecksCompanyOwnership;

class ReservationPolicy
{
    use ChecksCompanyOwnership;

    public function view(User $user, Reservation $reservation): bool
    {
        return $this->isReservationOwner($user, $reservation)
            || $this->userOwnsCompany($user, $this->resolveCompany($reservation));
    }

    public function update(User $user, Reservation $reservation): bool
    {
        return $this->isReservationOwner($user, $reservation)
            || $this->userOwnsCompany($user, $this->resolveCompany($reservation));
    }

    public function delete(User $user, Reservation $reservation): bool
    {
        return $this->isReservationOwner($user, $reservation)
            || $this->userOwnsCompany($user, $this->resolveCompany($reservation));
    }

    private function isReservationOwner(User $user, Reservation $reservation): bool
    {
        return (int) $user->id === (int) $reservation->user_id;
    }

    private function resolveCompany(Reservation $reservation): ?Company
    {
        if ($reservation->relationLoaded('company')) {
            return $reservation->getRelation('company');
        }

        if (!$reservation->company_id) {
            return null;
        }

        return $reservation->company;
    }
}
