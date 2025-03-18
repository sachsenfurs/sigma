<?php

namespace App\Observers;

use App\Models\Ddas\Dealer;
use App\Notifications\Ddas\ProcessedDealerNotification;

class DealerObserver
{

    public function updated(Dealer $dealer) {
        if($dealer->isDirty("approval")) {
            $dealer->user->notify(new ProcessedDealerNotification($dealer));
        }
    }

    public function deleted(Dealer $dealer): void {
        foreach($dealer->chats AS $chat) {
            $chat->subjectable()->dissociate();
            $chat->save();
        }
    }

}
