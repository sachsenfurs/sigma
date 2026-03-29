<?php

namespace App\Providers;

use App\Models\DepartmentInfo;
use App\Policies\Sig\DepartmentInfoPolicy;
use App\Models\SigEvent;
use App\Policies\Sig\SigEventPolicy;
use App\Models\SigFilledForm;
use App\Policies\Sig\SigFilledFormPolicy;
use App\Models\SigForm;
use App\Policies\Sig\SigFormPolicy;
use App\Models\SigHost;
use App\Policies\Sig\SigHostPolicy;
use App\Models\SigLocation;
use App\Policies\Sig\SigLocationPolicy;
use App\Models\SigTag;
use App\Policies\Sig\SigTagPolicy;
use App\Models\TimetableEntry;
use App\Policies\Sig\TimetableEntryPolicy;
use App\Models\SigAttendee;
use App\Policies\Sig\SigAttendeePolicy;
use App\Models\SigTimeslot;
use App\Policies\Sig\SigTimeslotPolicy;
use App\Models\User;
use App\Policies\User\UserPolicy;
use App\Models\UserRole;
use App\Policies\User\UserRolePolicy;
use App\Models\UserCalendar;
use App\Policies\User\UserCalendarPolicy;
use App\Models\Ddas\ArtshowArtist;
use App\Policies\Ddas\ArtshowArtistPolicy;
use App\Models\Ddas\ArtshowBid;
use App\Policies\Ddas\ArtshowBidPolicy;
use App\Models\Ddas\ArtshowItem;
use App\Policies\Ddas\ArtshowItemPolicy;
use App\Models\Ddas\ArtshowPickup;
use App\Policies\Ddas\ArtshowPickupPolicy;
use App\Models\Ddas\Dealer;
use App\Policies\Ddas\DealerPolicy;
use App\Models\Ddas\DealerTag;
use App\Policies\Ddas\DealerTagPolicy;
use App\Models\LostFoundItem;
use App\Policies\LostFoundItemPolicy;
use Spatie\LaravelSettings\Settings;
use App\Policies\Settings\SettingsPolicy;
use App\Models\Info\Social;
use App\Policies\SocialPolicy;
use App\Models\Post\Post;
use App\Policies\Post\PostPolicy;
use App\Models\Post\PostChannel;
use App\Policies\Post\PostChannelPolicy;
use App\Models\Chat;
use App\Policies\ChatPolicy;
use App\Models\Shift;
use App\Policies\Shift\ShiftPolicy;
use App\Models\ShiftType;
use App\Policies\Shift\ShiftTypePolicy;
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
        //
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot() {
//        Gate::before(function (User $user) {
//            if($user->isAdmin())
//                return true;
//            // IMPORTANT: don't return false!
//            // Otherwise it won't check for remaining policies and aborts the request IMMEDIATELY!
//        });


        Gate::policy(DepartmentInfo::class          , DepartmentInfoPolicy::class);
        Gate::policy(SigEvent::class                , SigEventPolicy::class);
        Gate::policy(SigFilledForm::class           , SigFilledFormPolicy::class);
        Gate::policy(SigForm::class                 , SigFormPolicy::class);
        Gate::policy(SigHost::class                 , SigHostPolicy::class);
        Gate::policy(SigLocation::class             , SigLocationPolicy::class);
        Gate::policy(SigTag::class                  , SigTagPolicy::class);
        Gate::policy(TimetableEntry::class          , TimetableEntryPolicy::class);
        Gate::policy(SigAttendee::class             , SigAttendeePolicy::class);
        Gate::policy(SigTimeslot::class             , SigTimeslotPolicy::class);

        Gate::policy(User::class                    , UserPolicy::class);
        Gate::policy(UserRole::class                , UserRolePolicy::class);
        Gate::policy(UserCalendar::class            , UserCalendarPolicy::class);


        Gate::policy(ArtshowArtist::class      , ArtshowArtistPolicy::class);
        Gate::policy(ArtshowBid::class         , ArtshowBidPolicy::class);
        Gate::policy(ArtshowItem::class        , ArtshowItemPolicy::class);
        Gate::policy(ArtshowPickup::class      , ArtshowPickupPolicy::class);
        Gate::policy(Dealer::class             , DealerPolicy::class);
        Gate::policy(DealerTag::class          , DealerTagPolicy::class);

        Gate::policy(LostFoundItem::class           , LostFoundItemPolicy::class);

        Gate::policy(Settings::class    , SettingsPolicy::class);

        Gate::policy(Social::class             , SocialPolicy::class);

        Gate::policy(Post::class               , PostPolicy::class);
        Gate::policy(PostChannel::class        , PostChannelPolicy::class);

        Gate::policy(Chat::class                    , ChatPolicy::class);

        Gate::policy(Shift::class                    , ShiftPolicy::class);
        Gate::policy(ShiftType::class                , ShiftTypePolicy::class);
    }
}
