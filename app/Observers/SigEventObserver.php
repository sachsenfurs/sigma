<?php

namespace App\Observers;

use App\Events\Sig\SigApplicationSubmitted;
use App\Models\SigEvent;
use App\Notifications\Sig\NewApplicationNotification;
use App\Facades\NotificationService;
use App\Notifications\Sig\ProcessedApplicationNotification;

class SigEventObserver
{
    public function applicationSubmitted(SigApplicationSubmitted $event): void {
        NotificationService::dispatchRoutedNotification(new NewApplicationNotification($event->sigEvent));
    }

    public function updated(SigEvent $sig): void {
        if($sig->isDirty("approval")) {
            foreach($sig->sigHosts->pluck("user") AS $user) {
                $user?->notify(new ProcessedApplicationNotification($sig));
            }
        }
    }

    public function subscribe(): array {
        // register custom events
        return [
            SigApplicationSubmitted::class => 'applicationSubmitted',
        ];
    }

    public function deleted(SigEvent $event): void {
        foreach($event->chats AS $chat) {
            $chat->subjectable()->dissociate();
            $chat->save();
        }
    }

}
