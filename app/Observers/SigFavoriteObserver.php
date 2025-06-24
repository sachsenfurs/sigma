<?php

namespace App\Observers;

use App\Models\Reminder;
use App\Models\SigFavorite;

class SigFavoriteObserver
{
    public function created(SigFavorite $sigFavorite) {
        if($sigFavorite->timetableEntry->start->isFuture()) {
            $reminder = new Reminder();
            $reminder->remindable()->associate($sigFavorite);
            $reminder->notifiable()->associate($sigFavorite->user);
            $reminder->save();
        }
    }

    public function deleted(SigFavorite $sigFavorite): void {
        $sigFavorite->reminders()->delete();
    }
}
