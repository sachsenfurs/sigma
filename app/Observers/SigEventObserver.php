<?php

namespace App\Observers;


use App\Models\SigEvent;
use App\Models\SigTag;

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


}
