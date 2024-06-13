<?php

namespace App\Policies;

use App\Settings\AppSettings;
use Illuminate\Auth\Access\Response;

class LostFoundItemPolicy
{
    public function viewAny(): bool|Response {
        return app(AppSettings::class)->lost_found_enabled ? true : Response::denyAsNotFound();
    }
}
