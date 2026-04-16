<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Business;

class BusinessPolicy
{
    /**
     * Whether the user can manage the business.
     */
    public function update(User $user, Business $business): bool
    {
        return $user->id === $business->user_id;
    }

    /**
     * Whether the user can delete the business.
     */
    public function delete(User $user, Business $business): bool
    {
        return $user->id === $business->user_id;
    }

    /**
     * Whether the user can view reservations.
     */
    public function viewReservations(User $user, Business $business): bool
    {
        return $user->id === $business->user_id || $user->worksBusiness($business);
    }
}
