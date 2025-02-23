<?php

namespace App\Notifications\Sig;

use App\Notifications\TimetableEntry\TimetableEntryReminder;

class SigFavoriteReminder extends TimetableEntryReminder
{
    public static function getName(): string {
        return __("Reminder for favorite events");
    }

}
