<?php

namespace App\Listeners\Ddas;

use App\Events\Ddas\DealerApplicationSubmitted;
use App\Notifications\Ddas\SubmittedDealerApplicationNotification;

class SendDealerApplicationSubmittedNotification
{
    public function handle(DealerApplicationSubmitted $event): void {
        $event->dealer->user->notify(new SubmittedDealerApplicationNotification($event->dealer));
    }
}
