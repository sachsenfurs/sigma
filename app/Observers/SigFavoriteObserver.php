<?php

namespace App\Observers;

use App\Models\SigFavorite;
use App\Models\SigReminder;

class SigFavoriteObserver
{
    /**
     * Handle the SigFavorite "creating" event.
     *
     * @param  \App\Models\SigFavorite  $sigFavorite
     * @return void
     */
    public function creating(SigFavorite $sigFavorite)
    {
        if (SigFavorite::where('user_id', $sigFavorite->user_id)->where('timetable_entry_id', $sigFavorite->timetable_entry_id)->exists()) {
            return false;
        }
    }

    /**
     * Handle the SigFavorite "updated" event.
     *
     * @param  \App\Models\SigFavorite  $sigFavorite
     * @return void
     */
    public function updated(SigFavorite $sigFavorite)
    {
        //
    }

    /**
     * Handle the SigFavorite "deleted" event.
     *
     * @param  \App\Models\SigFavorite  $sigFavorite
     * @return void
     */
    public function deleted(SigFavorite $sigFavorite)
    {
        if (SigReminder::where('user_id', $sigFavorite->user_id)->where('timetable_entry_id', $sigFavorite->timetable_entry_id)->exists()) {
            $reminder = SigReminder::where('user_id', $sigFavorite->user_id)->where('timetable_entry_id', $sigFavorite->timetable_entry_id)->first();
            $reminder->delete();
        }
    }

    /**
     * Handle the SigFavorite "restored" event.
     *
     * @param  \App\Models\SigFavorite  $sigFavorite
     * @return void
     */
    public function restored(SigFavorite $sigFavorite)
    {
        //
    }

    /**
     * Handle the SigFavorite "force deleted" event.
     *
     * @param  \App\Models\SigFavorite  $sigFavorite
     * @return void
     */
    public function forceDeleted(SigFavorite $sigFavorite)
    {
        //
    }
}
