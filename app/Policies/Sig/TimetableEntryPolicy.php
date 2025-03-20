<?php

namespace App\Policies\Sig;

use App\Enums\Permission;
use App\Enums\PermissionLevel;
use App\Models\TimetableEntry;
use App\Models\User;
use App\Settings\AppSettings;

class TimetableEntryPolicy extends ManageEventPolicy
{

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
        if($user?->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::READ))
            return true;

        if(!self::isSchedulePublic())
            return false;

        return true;
    }

    public function view(?User $user, TimetableEntry $timetableEntry): bool {
        if($user?->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::READ))
            return true;

        if(!self::isSchedulePublic())
            return false;
        if($timetableEntry->hide)
            return false;

        return true;
    }

    public function create(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function update(User $user, TimetableEntry $timetableEntry): bool {
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function delete(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::DELETE))
            return true;

        return false;
    }

    public function restore(User $user): bool {
        return false;
    }

    public function forceDelete(User $user): bool {
        return false;
    }

    public function associate(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function attach(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function deleteAny(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::DELETE))
            return true;

        return false;
    }

    public function detach(User $user, TimetableEntry $timetableEntry): bool {
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function detachAny(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function disassociate(User $user, TimetableEntry $timetableEntry): bool {
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function disassociateAny(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function forceDeleteAny(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::DELETE))
            return true;

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
