<?php

namespace App\Policies;

use App\Models\LostFoundItem;
use App\Models\User;
use App\Settings\AppSettings;
use Illuminate\Auth\Access\Response;

class LostFoundItemPolicy
{
    public function viewAny(): bool {
        return app(AppSettings::class)->lost_found_enabled;
    }
}
