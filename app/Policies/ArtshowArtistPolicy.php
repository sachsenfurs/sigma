<?php

namespace App\Policies;

use App\Models\DDAS\ArtshowArtist;
use App\Models\User;

class ArtshowArtistPolicy
{
    public function viewAny(User $user): bool {
        return $user->permissions()->contains('manage_artshow');
    }

    public function view(User $user, ArtshowArtist $artshowArtist): bool {
        return $user->permissions()->contains('manage_artshow') || $artshowArtist->user_id === $user->id;
    }

    public function create(User $user): bool {
        // allow admin OR user when not signed up yet
        return $user->permissions()->contains('manage_artshow') || $user->artists()->count() == 0;
    }

    public function update(User $user, ArtshowArtist $artshowArtist): bool {
        return $user->permissions()->contains('manage_artshow') || $artshowArtist->user_id === $user->id;
    }

    public function delete(User $user, ArtshowArtist $artshowArtist): bool {
        return $user->permissions()->contains('manage_artshow');
    }

    public function restore(User $user, ArtshowArtist $artshowArtist): bool {
        return $user->permissions()->contains('manage_artshow');
    }

    public function forceDelete(User $user, ArtshowArtist $artshowArtist): bool {
        return $user->permissions()->contains('manage_artshow');
    }

    public function associate(User $user): bool {
        return $user->permissions()->contains('manage_artshow');
    }

    public function attach(User $user): bool {
        return $user->permissions()->contains('manage_artshow');
    }

    public function deleteAny(User $user): bool {
        return $user->permissions()->contains('manage_artshow');
    }

    public function detach(User $user, ArtshowArtist $artshowArtist): bool {
        return $user->permissions()->contains('manage_artshow');
    }

    public function detachAny(User $user): bool {
        return $user->permissions()->contains('manage_artshow');
    }

    public function disassociate(User $user, ArtshowArtist $artshowArtist): bool {
        return $user->permissions()->contains('manage_artshow');
    }

    public function disassociateAny(User $user): bool {
        return $user->permissions()->contains('manage_artshow');
    }

    public function forceDeleteAny(User $user): bool {
        return $user->permissions()->contains('manage_artshow');
    }

    public function reorder(User $user): bool {
        return $user->permissions()->contains('manage_artshow');
    }

    public function replicate(User $user, ArtshowArtist $artshowArtist): bool {
        return $user->permissions()->contains('manage_artshow');
    }

    public function restoreAny(User $user): bool {
        return $user->permissions()->contains('manage_artshow');
    }
}
