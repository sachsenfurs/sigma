<?php

namespace App\Policies;

use App\Models\Ddas\ArtshowArtist;
use App\Models\Ddas\ArtshowItem;
use App\Models\User;
use App\Observers\ArtshowItemObserver;
use App\Settings\ArtShowSettings;

class ArtshowItemPolicy
{

    /**
     * Overrides
     */

    public function before(User $user): bool|null {
        // admin can do everything
        if($user->permissions()->contains('manage_artshow'))
            return true;

        return null;
    }

    /**
     * Helper functions
     */

    private function isWithinDeadline() {
        return app(ArtShowSettings::class)->item_deadline->isAfter(now());
    }
    private function isItemInAuction(ArtshowItem $artshowItem) {
        return ($artshowItem->approved || $artshowItem->sold || $artshowItem->paid || $artshowItem->artshowBids()->count() > 0);
    }
    private function isOwnItem(User $user, ArtshowItem $artshowItem) {
        return $artshowItem?->artist()->first()?->user_id === $user->id;
    }


    /**
     * Default abilities
     */

    public function viewAny(User $user): bool {
        return false;
    }

    public function view(User $user, ArtshowItem $artshowItem): bool {
        return $this->isOwnItem($user, $artshowItem);
    }

    public function create(User $user, ?ArtshowArtist $artshowArtist=null): bool {
        if(!$this->isWithinDeadline())
            return false;

        return $artshowArtist?->user_id == auth()->user()->id;
    }

    public function update(User $user, ArtshowItem $artshowItem): bool {
        if(!$this->isWithinDeadline())
            return false;

        // allow as long as item is not yet in auction
        return $this->isOwnItem($user, $artshowItem) AND !$this->isItemInAuction($artshowItem);
    }

    public function delete(User $user, ArtshowItem $artshowItem): bool {
        if(!$this->isWithinDeadline())
            return false;

        // otherwise scope to own items:
        if($this->isOwnItem($user, $artshowItem)) {
            // allow as long as item is not yet in auction
            return !$this->isItemInAuction($artshowItem);
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
        return false;
    }

    public function attach(User $user): bool {
        return false;
    }

    public function deleteAny(User $user): bool {
        return false;
    }

    public function detach(User $user, ArtshowItem $artshowItem): bool {
        return false;
    }

    public function detachAny(User $user): bool {
        return false;
    }

    public function disassociate(User $user, ArtshowItem $artshowItem): bool {
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

    public function replicate(User $user, ArtshowItem $artshowItem): bool {
        return false;
    }

    public function restoreAny(User $user): bool {
        return false;
    }
}
