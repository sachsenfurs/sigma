<?php

namespace App\Policies\Ddas;

use App\Enums\Permission;
use App\Enums\PermissionLevel;
use App\Models\Ddas\DealerTag;
use App\Models\User;
use App\Settings\DealerSettings;
use Illuminate\Auth\Access\Response;

class DealerTagPolicy
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
     * Default abilities
     */

    public function viewAny(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_DEALERS, PermissionLevel::READ))
            return true;

        return false;
    }

    public function view(User $user, DealerTag $tag): bool {
        if($user->hasPermission(Permission::MANAGE_DEALERS, PermissionLevel::READ))
            return true;

        return false;
    }



    public function restore(User $user, DealerTag $tag): bool {
        return false;
    }

    public function forceDelete(User $user, DealerTag $tag): bool {
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

    public function detach(User $user, DealerTag $tag): bool {
        if($user->hasPermission(Permission::MANAGE_DEALERS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function detachAny(User $user): bool {
        if($user->hasPermission(Permission::MANAGE_DEALERS, PermissionLevel::WRITE))
            return true;

        return false;
    }

    public function disassociate(User $user, DealerTag $tag): bool {
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

    public function replicate(User $user, DealerTag $tag): bool {
        return false;
    }

    public function restoreAny(User $user): bool {
        return false;
    }
}
