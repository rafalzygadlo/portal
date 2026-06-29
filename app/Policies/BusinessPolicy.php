<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Business;
use App\Policies\Concerns\ChecksBusinessOwnership;

class BusinessPolicy
{
    use ChecksBusinessOwnership;


    public function manage(User $user, Business $business): bool
    {
        return $this->userOwnsBusiness($user, $business);

    }
    /**
     * Whether the user can manage the business.
     */
    public function update(User $user, Business $business): bool
    {
        return $this->userOwnsBusiness($user, $business);
    }

    /**
     * Whether the user can delete the business.
     */
    public function delete(User $user, Business $business): bool
    {
        return $this->userOwnsBusiness($user, $business);
    }

    /**
     * Whether the user can view reservations.
     */
    public function viewReservations(User $user, Business $business): bool
    {
        return $this->userOwnsBusiness($user, $business);
    }
}
