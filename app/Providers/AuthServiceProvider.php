<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\SigEvent' => 'App\Policies\SigEventPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('manage_settings', function ($user) {
            return $user->role->perm_manage_settings;
        });

        Gate::define('manage_users', function ($user) {
            return $user->role->perm_manage_users;
        });

        Gate::define('manage_events', function ($user) {
            return $user->role->perm_manage_events;
        });

        Gate::define('manage_locations', function ($user) {
            return $user->role->perm_manage_locations;
        });

        Gate::define('manage_hosts', function ($user) {
            return $user->role->perm_manage_hosts;
        });
    }
}
