<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Business;

class BusinessPolicy
{
    /**
     * Czy użytkownik może zarządzać biznesem.
     */
    public function update(User $user, Business $business): bool
    {
        return $user->id === $business->user_id;
    }

    /**
     * Czy użytkownik może usunąć biznes.
     */
    public function delete(User $user, Business $business): bool
    {
        return $user->id === $business->user_id;
    }

    /**
     * Czy użytkownik może przeglądać rezerwacje.
     */
    public function viewReservations(User $user, Business $business): bool
    {
        return $user->id === $business->user_id || $user->worksBusiness($business);
    }
}
