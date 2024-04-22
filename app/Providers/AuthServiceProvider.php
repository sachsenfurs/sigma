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
        'App\Models\SigFilledForms' => 'App\Policies\SigFilledFormsPolicy',
        'App\Models\SigForms' => 'App\Policies\SigFormsPolicy',
        'App\Models\SigHost' => 'App\Policies\SigHostPolicy',
        'App\Models\SigLocation' => 'App\Policies\SigLocationPolicy',
        'App\Models\SigTag' => 'App\Policies\SigTagPolicy',
        'App\Models\SigTimeslot' => 'App\Policies\SigTimeslotPolicy',
        'App\Models\TimetableEntry' => 'App\Policies\TimetableEntryPolicy',
        'App\Models\User' => 'App\Policies\UserPolicy',
        'App\Models\UserRole' => 'App\Policies\UserRolePolicy',
        'App\Models\UserUserRole' => 'App\Policies\UserUserRolePolicy',
        'App\Models\DDAS\ArtshowArtist' => 'App\Policies\ArtshowArtistPolicy',
        'App\Models\DDAS\ArtshowBid' => 'App\Policies\ArtshowBidPolicy',
        'App\Models\DDAS\ArtshowItem' => 'App\Policies\ArtshowItemPolicy',
        'App\Models\DDAS\ArtshowPickup' => 'App\Policies\ArtshowPickupPolicy',
        'App\Models\DDAS\Dealer' => 'App\Policies\DealerPolicy',
        'App\Models\DDAS\DealerTag' => 'App\Policies\DealerTagPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        Gate::before(function ($user, $permission) {
            return $user->permissions()->contains($permission);
        });
    }
}
