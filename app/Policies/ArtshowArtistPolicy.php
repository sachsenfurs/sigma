<?php

namespace App\Policies;

use App\Models\DDAS\ArtshowArtist;
use App\Models\User;

class ArtshowArtistPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->permissions()->contains('manage_artshow');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ArtshowArtist $artshowArtist): bool
    {
        return $user->permissions()->contains('manage_artshow') || $artshowArtist->user_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->permissions()->contains('manage_artshow');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ArtshowArtist $artshowArtist): bool
    {
        return $user->permissions()->contains('manage_artshow') || $artshowArtist->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ArtshowArtist $artshowArtist): bool
    {
        return $user->permissions()->contains('manage_artshow');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ArtshowArtist $artshowArtist): bool
    {
        return $user->permissions()->contains('manage_artshow');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ArtshowArtist $artshowArtist): bool
    {
        return $user->permissions()->contains('manage_artshow');
    }

    public function associate(User $user): bool
    {
        return $user->permissions()->contains('manage_artshow');
    }

    public function attach(User $user): bool
    {
        return $user->permissions()->contains('manage_artshow');
    }

    public function deleteAny(User $user): bool
    {
        return $user->permissions()->contains('manage_artshow');
    }

    public function detach(User $user, ArtshowArtist $artshowArtist): bool
    {
        return $user->permissions()->contains('manage_artshow');
    }

    public function detachAny(User $user): bool
    {
        return $user->permissions()->contains('manage_artshow');
    }

    public function disassociate(User $user, ArtshowArtist $artshowArtist): bool
    {
        return $user->permissions()->contains('manage_artshow');
    }

    public function disassociateAny(User $user): bool
    {
        return $user->permissions()->contains('manage_artshow');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->permissions()->contains('manage_artshow');
    }

    public function reorder(User $user): bool
    {
        return $user->permissions()->contains('manage_artshow');
    }

    public function replicate(User $user, ArtshowArtist $artshowArtist): bool
    {
        return $user->permissions()->contains('manage_artshow');
    }

    public function restoreAny(User $user): bool
    {
        return $user->permissions()->contains('manage_artshow');
    }
}