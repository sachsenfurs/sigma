<?php

namespace App\Policies;

use App\Models\TimetableEntry;
use App\Models\User;
use App\Settings\AppSettings;
use Illuminate\Auth\Access\HandlesAuthorization;

class TimetableEntryPolicy
{
    use HandlesAuthorization;

    /**
     * Overrides
     */

    public function before(?User $user): bool|null {
        if($user)
            if($user->permissions()->contains('manage_events') || $user->permissions()->contains('manage_sigs'))
                return true;
        return null;
    }


    /**
     * Helper functions
     */

    public static function isSchedulePublic(): bool {
        return app(AppSettings::class)->show_schedule_date->isBefore(now());
    }

    /**
     * Default abilities
     */

    public function viewAny(?User $user): bool {
        if(!self::isSchedulePublic())
            return false;

        return true;
    }

    public function view(?User $user, TimetableEntry $timetableEntry): bool {
        if(!self::isSchedulePublic())
            return false;
        if($timetableEntry->hide)
            return false;

        return true;
    }

    public function create(User $user): bool {
        return false;
    }

    public function update(User $user, TimetableEntry $timetableEntry): bool {
        return false;
    }

    public function delete(User $user): bool {
        return false;
    }

    public function restore(User $user): bool {
        return false;
    }

    public function forceDelete(User $user): bool {
        return false;
    }

    public function associate(User $user): bool {
        return false;
    }

    public function attach(User $user): bool {
        return false;
    }

    public function deleteAny(User $user): bool {
        return false;
    }

    public function detach(User $user, TimetableEntry $timetableEntry): bool {
        return false;
    }

    public function detachAny(User $user): bool {
        return false;
    }

    public function disassociate(User $user, TimetableEntry $timetableEntry): bool {
        return false;
    }

    public function disassociateAny(User $user): bool {
        return false;
    }

    public function forceDeleteAny(User $user): bool {
        return false;
    }

    public function reorder(User $user): bool {
        return false;
    }

    public function replicate(User $user, TimetableEntry $timetableEntry): bool {
        return false;
    }

    public function restoreAny(User $user): bool {
        return false;
    }
}
