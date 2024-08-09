<?php

namespace App\Policies\Ddas;

use App\Models\Ddas\ArtshowBid;
use App\Models\Ddas\ArtshowItem;
use App\Models\User;
use App\Settings\ArtShowSettings;

class ArtshowBidPolicy //extends ManageArtshowPolicy
{

    private static function isBiddingOpen(): bool {
        return app(ArtShowSettings::class)->bid_start_date->isBefore(now()) AND app(ArtShowSettings::class)->bid_end_date->isAfter(now());
    }

    /**
     * Default abilities
     */

    public function viewAny(User $user): bool {
        return false;
    }

    public function view(User $user, ArtshowBid $artshowBid): bool {
        return false;
    }

    public function create(User $user, ArtshowItem $artshowItem): bool {
        // not in auction, not locked and not sold
        if(!$artshowItem->auction OR $artshowItem->locked OR $artshowItem->sold)
            return false;

        // prohibit bidding on own items
        if($user->artists->pluck("id")->contains($artshowItem->artshow_artist_id))
            return false;

        if(ArtshowItemPolicy::isArtshowPublic())
            return self::isBiddingOpen();
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
