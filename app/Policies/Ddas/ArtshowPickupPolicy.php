<?php

namespace App\Policies\Ddas;

use App\Models\Ddas\ArtshowPickup;
use App\Models\User;

class ArtshowPickupPolicy extends ManageArtshowPolicy
{

    /**
     * Default abilities
     */

    public function viewAny(User $user): bool {
        return false;
    }

    public function view(User $user, ArtshowPickup $artshowPickup): bool {
        return false;
    }

    public function create(User $user): bool {
        return false;
    }

    public function update(User $user, ArtshowPickup $artshowPickup): bool {
        return false;
    }

    public function delete(User $user, ArtshowPickup $artshowPickup): bool {
        return false;
    }

    public function restore(User $user, ArtshowPickup $artshowPickup): bool {
        return false;
    }

    public function forceDelete(User $user, ArtshowPickup $artshowPickup): bool {
        return false;
    }
}
