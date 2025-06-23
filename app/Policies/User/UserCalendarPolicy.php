<?php

namespace App\Policies\User;

use App\Models\User;
use App\Models\UserCalendar;

class UserCalendarPolicy
{
    public function create(User $user): bool {
        if($user->calendars()->count() < 4)
            return true;

        return false;
    }

    public function delete(User $user, UserCalendar $calendar): bool {
        return $calendar->user_id == $user->id;
    }

    public function update(User $user, UserCalendar $calendar): bool {
        return $calendar->user_id == $user->id;
    }
}
