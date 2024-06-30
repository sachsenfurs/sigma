<?php

namespace App\Policies\Ddas;

use App\Enums\Permission;
use App\Enums\PermissionLevel;
use App\Models\User;
use App\Settings\ArtShowSettings;
use Illuminate\Auth\Access\Response;

class ManageArtshowPolicy
{

    /**
     * Overrides
     */

    public function before(User $user): bool|null|Response {
        if(!app(ArtShowSettings::class)->enabled)
            return Response::denyAsNotFound();

        // admin can do everything
        if($user->hasPermission(Permission::MANAGE_ARTSHOW, PermissionLevel::ADMIN))
            return true;

        return null;
    }

}
