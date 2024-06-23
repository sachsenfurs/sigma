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
        \App\Models\Chat::class                 => \App\Policies\ChatPolicy::class,
        \App\Models\Ddas\ArtshowArtist::class   => \App\Policies\ArtshowArtistPolicy::class,
        \App\Models\Ddas\ArtshowBid::class      => \App\Policies\ArtshowBidPolicy::class,
        \App\Models\Ddas\ArtshowItem::class     => \App\Policies\ArtshowItemPolicy::class,
        \App\Models\Ddas\ArtshowPickup::class   => \App\Policies\ArtshowPickupPolicy::class,
        \App\Models\Ddas\Dealer::class          => \App\Policies\DealerPolicy::class,
        \App\Models\Ddas\DealerTag::class       => \App\Policies\DealerTagPolicy::class,
        \App\Models\LostFoundItem::class        => \App\Policies\LostFoundItemPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot() {
        Gate::before(function ($user, $permission) {
            if($user->permissions()->contains($permission))
                return true;
            // IMPORTANT: don't return false!
            // Otherwise it won't check for remaining policies and aborts the request IMMEDIATELY!
        });
    }
}
