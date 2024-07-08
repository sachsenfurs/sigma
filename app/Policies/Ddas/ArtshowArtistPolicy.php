<?php

namespace App\Policies\Ddas;

use App\Enums\Permission;
use App\Enums\PermissionLevel;
use App\Models\Ddas\ArtshowArtist;
use App\Models\User;

class ArtshowArtistPolicy extends ManageArtshowPolicy
{

    /**
     * Default abilities
     */

    public function viewAny(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_ARTSHOW, PermissionLevel::READ))
            return true;

        return false;
    }

    public function view(User $user, ArtshowArtist $artshowArtist): bool {
        if($user->hasPermission(Permission::MANAGE_ARTSHOW, PermissionLevel::READ))
            return true;
        if($artshowArtist->user_id === $user->id)
            return true;

        return false;
    }

    public function create(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_ARTSHOW, PermissionLevel::WRITE))
            return true;

        // allow when not signed up yet
        if($user->artists()->count() == 0)
            return true;

        return false;
    }

    public function update(User $user, ArtshowArtist $artshowArtist): bool {
        if($user->hasPermission(Permission::MANAGE_ARTSHOW, PermissionLevel::WRITE))
            return true;
        if($artshowArtist->user_id === $user->id)
            return true;

        return false;
    }

    public function delete(User $user, ArtshowArtist $artshowArtist): bool {
        if($user->hasPermission(Permission::MANAGE_ARTSHOW, PermissionLevel::DELETE))
            return true;

        return false;
    }

    public function restore(User $user, ArtshowArtist $artshowArtist): bool {
        return false;
    }

    public function forceDelete(User $user, ArtshowArtist $artshowArtist): bool {
        return false;
    }

    public function associate(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_ARTSHOW, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function attach(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_ARTSHOW, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function deleteAny(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_ARTSHOW, PermissionLevel::DELETE))
            return true;

        return false;
    }

    public function detach(User $user, ArtshowArtist $artshowArtist): bool {
        if($user->hasPermission(Permission::MANAGE_ARTSHOW, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function detachAny(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_ARTSHOW, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function disassociate(User $user, ArtshowArtist $artshowArtist): bool {
        if($user->hasPermission(Permission::MANAGE_ARTSHOW, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function disassociateAny(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_ARTSHOW, PermissionLevel::WRITE))
            return true;

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
