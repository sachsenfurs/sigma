<?php

namespace App\Policies\Ddas;

use App\Models\Ddas\ArtshowBid;
use App\Models\User;

class ArtshowBidPolicy extends ManageArtshowPolicy
{

    /**
     * Default abilities
     */

    public function viewAny(User $user): bool {
        return false;
    }

    public function view(User $user, ArtshowBid $artshowBid): bool {
        return false;
    }

    public function create(User $user): bool {
        return false;
    }

    public function update(User $user, ArtshowBid $artshowBid): bool {
        return false;
    }

    public function delete(User $user, ArtshowBid $artshowBid): bool {
        return false;
    }

    public function restore(User $user, ArtshowBid $artshowBid): bool {
        return false;
    }

    public function forceDelete(User $user, ArtshowBid $artshowBid): bool {
        return false;
    }
}
