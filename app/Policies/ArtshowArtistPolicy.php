<?php

namespace App\Policies;

use App\Models\Ddas\ArtshowArtist;
use App\Models\User;
use App\Settings\ArtShowSettings;
use Illuminate\Auth\Access\Response;

class ArtshowArtistPolicy
{

    /**
     * Overrides
     */

    public function before(User $user): bool|null|Response {
        if(!app(ArtShowSettings::class)->enabled)
            return Response::denyAsNotFound();

        // admin can do everything
        if($user->permissions()->contains('manage_artshow'))
            return true;

        return null;
    }

    /**
     * Helper functions
     */

    // ..

    /**
     * Default abilities
     */

    public function viewAny(User $user): bool {
        return false;
    }

    public function view(User $user, ArtshowArtist $artshowArtist): bool {
        return $artshowArtist->user_id === $user->id;
    }

    public function create(User $user): bool {
        // allow when not signed up yet
        return $user->artists()->count() == 0;
    }

    public function update(User $user, ArtshowArtist $artshowArtist): bool {
        return $artshowArtist->user_id === $user->id;
    }

    public function delete(User $user, ArtshowArtist $artshowArtist): bool {
        return false;
    }

    public function restore(User $user, ArtshowArtist $artshowArtist): bool {
        return false;
    }

    public function forceDelete(User $user, ArtshowArtist $artshowArtist): bool {
        return false;
    }

    public function associate(User $user): bool {
        return false;
    }

    public function attach(User $user): bool {
        return false;
    }

    public function deleteAny(User $user): bool {
        return false;
    }

    public function detach(User $user, ArtshowArtist $artshowArtist): bool {
        return false;
    }

    public function detachAny(User $user): bool {
        return false;
    }

    public function disassociate(User $user, ArtshowArtist $artshowArtist): bool {
        return false;
    }

    public function disassociateAny(User $user): bool {
        return false;
    }

    public function forceDeleteAny(User $user): bool {
        return false;
    }

    public function reorder(User $user): bool {
        return false;
    }

    public function replicate(User $user, ArtshowArtist $artshowArtist): bool {
        return false;
    }

    public function restoreAny(User $user): bool {
        return false;
    }
}
