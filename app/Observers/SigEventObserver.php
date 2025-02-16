<?php

namespace App\Observers;

use App\Events\Sig\SigApplicationSubmitted;
use App\Models\SigEvent;
use App\Notifications\Sig\NewSigApplicationNotification;
use App\Facades\NotificationService;

class SigEventObserver
{
    public function applicationSubmitted(SigApplicationSubmitted $event): void {
        NotificationService::dispatchRoutedNotification(new NewSigApplicationNotification($event->sigEvent));
    }

    public function updated(SigEvent $sig) {
        // If the event is approved, create a new chat for better communication
        //if($sig->approved) {
        //    $chat = Chat::new($sig->name);
        //    $host = User::where('reg_id', $sig->sigHost()->reg_id)->first();
        //    $chat->users()->attach($host->id);
        //    $chat->save();
        //}
    }

    public function subscribe(): array {
        // register observer
        SigEvent::observe(SigEventObserver::class);

        // register custom events
        return [
            SigApplicationSubmitted::class => 'applicationSubmitted',
        ];
    }


}
