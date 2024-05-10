<?php

namespace App\Policies;

use App\Models\DDAS\ArtshowArtist;
use App\Models\DDAS\ArtshowItem;
use App\Models\User;
use App\Observers\ArtshowItemObserver;

class ArtshowItemPolicy
{

    private function isItemInAuction(ArtshowItem $artshowItem) {
        return ($artshowItem->approved || $artshowItem->sold || $artshowItem->paid || $artshowItem->artshowBids()->count() > 0);
    }
    private function isOwnItem(User $user, ArtshowItem $artshowItem) {
        return $artshowItem?->artist()->first()?->user_id === $user->id;
    }


    public function viewAny(User $user): bool {
        return $user->permissions()->contains('manage_artshow');
    }


    public function view(User $user, ArtshowItem $artshowItem): bool {
        return $user->permissions()->contains('manage_artshow') || $this->isOwnItem($user, $artshowItem);
    }

    public function create(User $user, ?ArtshowArtist $artshowArtist=null): bool {
        return $user->permissions()->contains('manage_artshow') || $artshowArtist?->user_id == auth()->user()->id;
    }

    public function update(User $user, ArtshowItem $artshowItem): bool {
        // admin can do everything
        if($user->permissions()->contains('manage_artshow'))
            return true;

        // allow as long as item is not yet in auction
        return $this->isOwnItem($user, $artshowItem) AND !$this->isItemInAuction($artshowItem);
    }

    public function delete(User $user, ArtshowItem $artshowItem): bool {
        // admin can do everything
        if($user->permissions()->contains('manage_artshow'))
            return true;

        // otherwise scope to own items:
        if($this->isOwnItem($user, $artshowItem)) {
            // allow as long as item is not yet in auction
            return !$this->isItemInAuction($artshowItem);
        }
        return false;
    }

    public function restore(User $user, ArtshowItem $artshowItem): bool {
        return $user->permissions()->contains('manage_artshow');
    }

    public function forceDelete(User $user, ArtshowItem $artshowItem): bool {
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

    public function detach(User $user, ArtshowItem $artshowItem): bool {
        return $user->permissions()->contains('manage_artshow');
    }

    public function detachAny(User $user): bool {
        return $user->permissions()->contains('manage_artshow');
    }

    public function disassociate(User $user, ArtshowItem $artshowItem): bool {
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

    public function replicate(User $user, ArtshowItem $artshowItem): bool {
        return $user->permissions()->contains('manage_artshow');
    }

    public function restoreAny(User $user): bool {
        return $user->permissions()->contains('manage_artshow');
    }
}
