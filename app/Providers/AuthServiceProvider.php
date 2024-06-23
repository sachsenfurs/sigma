<?php

namespace App\Providers;

use App\Enums\Permission;
use App\Enums\PermissionLevel;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot() {
        Gate::before(function (User $user) {
            if($user->hasPermission(Permission::MANAGE_ADMIN, PermissionLevel::ADMIN))
                return true;
            // IMPORTANT: don't return false!
            // Otherwise it won't check for remaining policies and aborts the request IMMEDIATELY!
        });


        Gate::policy(\App\Models\SigEvent::class                , \App\Policies\Sig\SigEventPolicy::class);
        Gate::policy(\App\Models\SigFilledForm::class           , \App\Policies\Sig\SigFilledFormPolicy::class);
        Gate::policy(\App\Models\SigForm::class                 , \App\Policies\Sig\SigFormPolicy::class);
        Gate::policy(\App\Models\SigHost::class                 , \App\Policies\Sig\SigHostPolicy::class);
        Gate::policy(\App\Models\SigLocation::class             , \App\Policies\Sig\SigLocationPolicy::class);
        Gate::policy(\App\Models\SigTag::class                  , \App\Policies\Sig\SigTagPolicy::class);
        Gate::policy(\App\Models\TimetableEntry::class          , \App\Policies\Sig\TimetableEntryPolicy::class);

        Gate::policy(\App\Models\User::class                    , \App\Policies\User\UserPolicy::class);
        Gate::policy(\App\Models\UserRole::class                , \App\Policies\User\UserRolePolicy::class);

        Gate::policy(\App\Models\Ddas\ArtshowArtist::class      , \App\Policies\Ddas\ArtshowArtistPolicy::class);
        Gate::policy(\App\Models\Ddas\ArtshowBid::class         , \App\Policies\Ddas\ArtshowBidPolicy::class);
        Gate::policy(\App\Models\Ddas\ArtshowItem::class        , \App\Policies\Ddas\ArtshowItemPolicy::class);
        Gate::policy(\App\Models\Ddas\ArtshowPickup::class      , \App\Policies\Ddas\ArtshowPickupPolicy::class);
        Gate::policy(\App\Models\Ddas\Dealer::class             , \App\Policies\Ddas\DealerPolicy::class);
        Gate::policy(\App\Models\Ddas\DealerTag::class          , \App\Policies\Ddas\DealerPolicy::class);

        Gate::policy(\App\Models\LostFoundItem::class           , \App\Policies\LostFoundItemPolicy::class);

        Gate::policy(\Spatie\LaravelSettings\Settings::class    , \App\Policies\Settings\SettingsPolicy::class);

        Gate::policy(\App\Models\Info\Social::class             , \App\Policies\SocialPolicy::class);

        Gate::policy(\App\Models\Post\Post::class               , \App\Policies\Post\PostPolicy::class);
        Gate::policy(\App\Models\Post\PostChannel::class        , \App\Policies\Post\PostChannelPolicy::class);
    }
}
