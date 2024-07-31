<?php

namespace App\Policies\Ddas;

use App\Enums\Permission;
use App\Enums\PermissionLevel;
use App\Models\Ddas\ArtshowArtist;
use App\Models\Ddas\ArtshowItem;
use App\Models\User;
use App\Settings\ArtShowSettings;

class ArtshowItemPolicy extends ManageArtshowPolicy
{

    /**
     * Helper functions
     */

    private function isWithinDeadline() {
        return app(ArtShowSettings::class)->item_deadline->isAfter(now());
    }

    private function isOwnItem(User $user, ArtshowItem $artshowItem) {
        return $artshowItem?->artist()->first()?->user_id === $user->id;
    }


    /**
     * Default abilities
     */

    public function viewAny(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_ARTSHOW, PermissionLevel::READ))
            return true;

        return false;
    }

    public function view(User $user, ArtshowItem $artshowItem): bool {
        if($this->isOwnItem($user, $artshowItem))
            return true;
        if($user->hasPermission(Permission::MANAGE_ARTSHOW, PermissionLevel::READ))
            return true;

        return false;
    }

    public function create(User $user, ?ArtshowArtist $artshowArtist=null): bool {
        if($user->hasPermission(Permission::MANAGE_ARTSHOW, PermissionLevel::WRITE))
            return true;

        if(!$this->isWithinDeadline())
            return false;
        if($artshowArtist?->user_id == auth()->user()->id)
            return true;

        return false;
    }

    public function update(User $user, ArtshowItem $artshowItem): bool {
        if($user->hasPermission(Permission::MANAGE_ARTSHOW, PermissionLevel::WRITE))
            return true;

        if(!$this->isWithinDeadline())
            return false;

        // allow as long as item is not yet in auction
        if($this->isOwnItem($user, $artshowItem) AND !$artshowItem->isInAuction())
            return true;

        return false;
    }

    public function delete(User $user, ArtshowItem $artshowItem): bool {
        if($user->hasPermission(Permission::MANAGE_ARTSHOW, PermissionLevel::DELETE))
            return true;

        if(!$this->isWithinDeadline())
            return false;

        // otherwise scope to own items:
        if($this->isOwnItem($user, $artshowItem)) {
            // allow as long as item is not yet in auction
            return !$artshowItem->isInAuction();
        }

        return false;
    }

    public function restore(User $user, ArtshowItem $artshowItem): bool {
        return false;
    }

    public function forceDelete(User $user, ArtshowItem $artshowItem): bool {
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
        if($user->hasPermission(Permission::MANAGE_ARTSHOW, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function detach(User $user, ArtshowItem $artshowItem): bool {
        if($user->hasPermission(Permission::MANAGE_ARTSHOW, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function detachAny(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_ARTSHOW, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function disassociate(User $user, ArtshowItem $artshowItem): bool {
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

    public function replicate(User $user, ArtshowItem $artshowItem): bool {
        return false;
    }

    public function restoreAny(User $user): bool {
        return false;
    }
}
