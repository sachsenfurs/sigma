<?php

namespace App\Services;

use App\Models\Ddas\ArtshowItem;
use App\Models\User;
use App\Notifications\Ddas\ArtshowWinnerNotification;

class ArtshowNotificationService
{
    public static function notifyWinners() {
        $bidders = User::whereHas("artshowBids.artshowItem")->get();
        foreach($bidders AS $bidder) {
            $wonItems = ArtshowItem::where("sold", false)->whereHas("highestBid")->get()->filter(fn($item) => $item->highestBid?->user_id == $bidder->id);

            if($wonItems->count() > 0) {
                $bidder->notify((new ArtshowWinnerNotification($wonItems)));
                $wonItems->each->update([
                    'sold' => 1,
                ]);
            }
        }
    }
}
