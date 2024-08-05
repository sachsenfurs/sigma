<?php

namespace App\Policies\Ddas;

use App\Enums\Permission;
use App\Enums\PermissionLevel;
use App\Models\Ddas\ArtshowArtist;
use App\Models\Ddas\ArtshowItem;
use App\Models\User;
use App\Settings\ArtShowSettings;
use Illuminate\Auth\Access\Response;

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

    public static function isArtshowPublic(): bool {
        return app(ArtShowSettings::class)->show_items_date->isBefore(now());
    }


    /**
     * Default abilities
     */

    public function viewAny(User $user): Response {
        if($user->hasPermission(Permission::MANAGE_ARTSHOW, PermissionLevel::READ))
            return Response::allow();

        if(app(ArtShowSettings::class)->require_checkin AND !$user->checkedin)
            return Response::deny(__("You have to be checked in at convention site to view the art show. Please visit registration or con-ops and get your badge first"));

        return self::isArtshowPublic()
            ? Response::allow()
            : Response::deny();
    }

    public function view(User $user, ArtshowItem $artshowItem): Response {
        if($this->isOwnItem($user, $artshowItem))
            return Response::allow();
        if($user->hasPermission(Permission::MANAGE_ARTSHOW, PermissionLevel::READ))
            return Response::allow();

        if(self::isArtshowPublic()) {
            if($artshowItem->approved)
                return Response::allow();
        }
        return Response::deny();
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

    public function delete(User $user, ArtshowItem $artshowItem): Response {
        if($user->hasPermission(Permission::MANAGE_ARTSHOW, PermissionLevel::DELETE))
            return Response::deny();

        if(!$this->isWithinDeadline())
            return Response::deny();

        // otherwise scope to own items:
        if($this->isOwnItem($user, $artshowItem)) {
            // allow as long as item is not yet in auction or approved

            if($artshowItem->approved)
                return Response::deny();

            return $artshowItem->isInAuction()
                ? Response::deny(__("Item is already in auction"))
                : Response::allow();
        }

        return Response::deny();
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
