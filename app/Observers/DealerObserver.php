<?php

namespace App\Observers;

use App\Models\Ddas\Dealer;

class DealerObserver
{

    public function deleted(Dealer $dealer): void {
        foreach($dealer->chats AS $chat) {
            $chat->subjectable()->dissociate();
            $chat->save();
        }
    }

}
