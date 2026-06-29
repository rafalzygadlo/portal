<?php

namespace App\Policies;

use App\Models\Business;
use App\Models\Image;
use App\Models\User;
use App\Policies\Concerns\ChecksBusinessOwnership;

class ImagePolicy
{
    use ChecksBusinessOwnership;

    public function update(User $user, Image $image): bool
    {
        return $this->canManageImage($user, $image);
    }

    public function delete(User $user, Image $image): bool
    {
        return $this->canManageImage($user, $image);
    }

    private function canManageImage(User $user, Image $image): bool
    {
        $imageable = $image->relationLoaded('imageable') ? $image->getRelation('imageable') : $image->imageable;

        if (!$imageable) {
            return false;
        }

        if (isset($imageable->user_id)) {
            return (int) $user->id === (int) $imageable->user_id;
        }

        if ($imageable instanceof Business) {
            return $this->userOwnsBusiness($user, $imageable);
        }

        return false;
    }
}
