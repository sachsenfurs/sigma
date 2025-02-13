<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SessionStatsService
{
    public static function isSupported() {
        return Session::getDefaultDriver() == "database";
    }

    public static function getActiveSessionsCount(int $timeout=120): int {
        return Cache::remember(
            "active_sessions_count",
            60,
            fn() => DB::table("sessions")->where("last_activity",">", time() - $timeout)->count()
        );
    }

    public static function getActiveUsersCount(int $timeout=120): int {
        return Cache::remember(
            "active_users_count",
            60,
            fn() => DB::table("sessions")
                      ->where("last_activity",">", time() - $timeout)
                      ->whereNotNull("user_id")
                      ->count()
        );
    }

}
