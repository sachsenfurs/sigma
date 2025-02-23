<?php

namespace App\Models\Traits;

use App\Models\Reminder;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasReminders
{
    public function reminders(): MorphMany {
        return $this->morphMany(Reminder::class, "remindable");
    }

    public function userReminder(): MorphMany {
        return $this->reminders()->whereHasMorph("notifiable", User::class, function (Builder $query) {
             return $query->where("notifiable_id", auth()->id());
        });
    }
}
