<?php

namespace App\Policies\Ddas;

use App\Enums\Approval;
use App\Enums\Permission;
use App\Enums\PermissionLevel;
use App\Models\Ddas\Dealer;
use App\Models\User;
use App\Settings\DealerSettings;
use Illuminate\Auth\Access\Response;

class DealerPolicy
{
    /**
     * Overrides
     */

    public function before(User $user): bool|null|Response {
        if(!app(DealerSettings::class)->enabled)
            return Response::denyAsNotFound();
        if($user->hasPermission(Permission::MANAGE_DEALERS, PermissionLevel::ADMIN))
            return true;

        return null;
    }


    /**
     * Helper functions
     */

    public static function isWithinDeadline(): bool {
        return app(DealerSettings::class)->signup_deadline->isAfter(now());
    }

    /**
     * Default abilities
     */

    public function viewAny(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_DEALERS, PermissionLevel::READ))
            return true;

        return false;
    }

    public function view(User $user, Dealer $dealer): bool {
        if($user->hasPermission(Permission::MANAGE_DEALERS, PermissionLevel::READ))
            return true;
        if($dealer->user_id === $user->id)
            return true;

        return false;
    }

    public function create(User $user): bool|Response {
        if($user->hasPermission(Permission::MANAGE_DEALERS, PermissionLevel::WRITE))
            return true;
        if(!self::isWithinDeadline())
            return Response::deny(__("The deadline for dealers application has already passed"));
        if(app(DealerSettings::class)->paid_only AND !$user->paid)
            return Response::deny(__("You need a valid (paid) ticket for the event in order to sign up"));
        if($user->dealers()->count() == 0)
            return true;

        return false;
    }

    public function update(User $user, Dealer $dealer): bool {
        if($user->hasPermission(Permission::MANAGE_DEALERS, PermissionLevel::WRITE))
            return true;
        if($dealer->user_id === $user->id && $dealer->approval != Approval::APPROVED)
            return true;

        return false;
    }

    public function delete(User $user, Dealer $dealer): bool {
        if($user->hasPermission(Permission::MANAGE_DEALERS, PermissionLevel::DELETE))
            return true;

        return false;
    }

    public function restore(User $user, Dealer $dealer): bool {
        return false;
    }

    public function forceDelete(User $user, Dealer $dealer): bool {
        return false;
    }

    public function associate(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_DEALERS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function attach(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_DEALERS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function deleteAny(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_DEALERS, PermissionLevel::DELETE))
            return true;

        return false;
    }

    public function detach(User $user, Dealer $dealer): bool {
        if($user->hasPermission(Permission::MANAGE_DEALERS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function detachAny(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_DEALERS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function disassociate(User $user, Dealer $dealer): bool {
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

    public function replicate(User $user, Dealer $dealer): bool {
        return false;
    }

    public function restoreAny(User $user): bool {
        return false;
    }
}
