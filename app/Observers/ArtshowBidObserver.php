<?php

namespace App\Observers;

use App\Models\Ddas\ArtshowBid;
use App\Notifications\Ddas\ArtshowItemOutbidNotification;

class ArtshowBidObserver
{
    public function created(ArtshowBid $newBid): void {
        $lastBid    = $newBid->artshowItem->artshowBids()->orderBy("id", "desc")->where("user_id", "!=", $newBid->user_id)->first();
        if($lastBid AND $lastBid->user_id != $newBid->user_id AND $newBid->value > $lastBid->value) {
            $lastBid->user->notify(new ArtshowItemOutbidNotification($newBid->artshowItem, $lastBid, $newBid));
        }
    }
}
