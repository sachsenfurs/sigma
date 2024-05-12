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
        \App\Models\SigEvent::class             => \App\Policies\SigEventPolicy::class,
        \App\Models\SigFilledForm::class        => \App\Policies\SigFilledFormPolicy::class,
        \App\Models\SigForm::class              => \App\Policies\SigFormPolicy::class,
        \App\Models\SigHost::class              => \App\Policies\SigHostPolicy::class,
        \App\Models\SigLocation::class          => \App\Policies\SigLocationPolicy::class,
        \App\Models\SigTag::class               => \App\Policies\SigTagPolicy::class,
        \App\Models\TimetableEntry::class       => \App\Policies\TimetableEntryPolicy::class,
        \App\Models\User::class                 => \App\Policies\UserPolicy::class,
        \App\Models\UserRole::class             => \App\Policies\UserRolePolicy::class,
        \App\Models\UserUserRole::class         => \App\Policies\UserUserRolePolicy::class,
        \App\Models\DDAS\ArtshowArtist::class   => \App\Policies\ArtshowArtistPolicy::class,
        \App\Models\DDAS\ArtshowBid::class      => \App\Policies\ArtshowBidPolicy::class,
        \App\Models\DDAS\ArtshowItem::class     => \App\Policies\ArtshowItemPolicy::class,
        \App\Models\DDAS\ArtshowPickup::class   => \App\Policies\ArtshowPickupPolicy::class,
        \App\Models\DDAS\Dealer::class          => \App\Policies\DealerPolicy::class,
        \App\Models\DDAS\DealerTag::class       => \App\Policies\DealerTagPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot() {
        Gate::before(function ($user, $permission) {
            return $user->permissions()->contains($permission);
        });
    }
}
