<?php

namespace App\Observers;

use App\Events\Sig\SigApplicationSubmitted;
use App\Models\SigEvent;
use App\Notifications\Sig\NewSigApplicationNotification;
use App\Facades\NotificationService;
use App\Notifications\Sig\SigApplicationProcessedNotification;

class SigEventObserver
{
    public function applicationSubmitted(SigApplicationSubmitted $event): void {
        NotificationService::dispatchRoutedNotification(new NewSigApplicationNotification($event->sigEvent));
    }

    public function updated(SigEvent $sig) {
        if($sig->isDirty("approval")) {
            $sig->sigHosts->pluck("user")->each->notify(new SigApplicationProcessedNotification($sig));
        }
    }

    public function subscribe(): array {
        // register custom events
        return [
            SigApplicationSubmitted::class => 'applicationSubmitted',
        ];
    }


}
