<?php

namespace App\Policies\Ddas;

use App\Enums\Permission;
use App\Enums\PermissionLevel;
use App\Models\Ddas\ArtshowBid;
use App\Models\Ddas\ArtshowItem;
use App\Models\User;
use App\Settings\ArtShowSettings;
use Illuminate\Auth\Access\Response;

class ArtshowBidPolicy extends ManageArtshowPolicy
{

    private static function isBiddingOpen(): bool {
        return app(ArtShowSettings::class)->bid_start_date->isBefore(now()) AND app(ArtShowSettings::class)->bid_end_date->isAfter(now());
    }

    /**
     * Default abilities
     */

    public function viewAny(User $user): bool {
        return $user->hasPermission(Permission::MANAGE_ARTSHOW, PermissionLevel::READ);
    }

    public function view(User $user, ArtshowBid $artshowBid): bool {
        return $user->hasPermission(Permission::MANAGE_ARTSHOW, PermissionLevel::READ);
    }

    public function create(User $user, ?ArtshowItem $artshowItem=null): bool|Response {
        if(!$artshowItem)
            return false;

        // item approved?
        if(!$artshowItem->approved())
            return false;

        // not in auction, not locked and not sold
        if(!$artshowItem->auction OR $artshowItem->locked OR $artshowItem->sold)
            return false;

        // prohibit bidding on own items
        if($user->artists->pluck("id")->contains($artshowItem->artshow_artist_id))
            return Response::deny("Can't bid on own items!");

        if($artshowItem->artshowBids()->count() >= app(ArtShowSettings::class)->max_bids_per_item)
            return Response::deny(__("Maximum bids reached. Item will be sold in the auction!"));

        if(ArtshowItemPolicy::isArtshowPublic())
            return self::isBiddingOpen() ? true : Response::deny(__("No bids are currently being accepted for this item"));

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
