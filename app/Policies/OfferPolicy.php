<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Offer;

class OfferPolicy
{
    /**
     * Whether the user can update the offer.
     */
    public function update(User $user, Offer $offer): bool
    {
        return $user->id === $offer->user_id;
    }

    /**
     * Whether the user can delete the offer.
     */
    public function delete(User $user, Offer $offer): bool
    {
        return $user->id === $offer->user_id;
    }
}
