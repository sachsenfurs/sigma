<?php

namespace App\Observers;

use App\Models\Chat;
use App\Models\SigEvent;
use App\Models\SigTag;
use App\Models\User;

class SigEventObserver
{
    public function updated(SigEvent $sig)
    {
        // If the event is approved, create a new chat for better communication
        //if($sig->approved) {
        //    $chat = Chat::new($sig->name);
        //    $host = User::where('reg_id', $sig->sigHost()->reg_id)->first();
        //    $chat->users()->attach($host->id);
        //    $chat->save();
        //}
    }


}
