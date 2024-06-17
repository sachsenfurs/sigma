<?php

namespace App\Observers;

use App\Models\Chat;
use App\Models\SigEvent;
use App\Models\SigTag;
use App\Models\User;

class SigEventObserver
{
    public function created(SigEvent $sig)
    {
        // auto assign corresponding tags
        // mit absicht nur beim create, dass man die Möglichkeit hat
        // das auch wieder rauszunehmen, FALLS es mal doch nicht angezeigt werden soll
        foreach(SigTag::all() AS $tag) {
            if($tag->name == "signup") {
                if($sig->reg_possible)
                    $sig->sigTags()->syncWithoutDetaching($tag->id); // attach würde ausreichen. hatte es nur vorher in der update-Methode drin...
            }
        }
    }

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
