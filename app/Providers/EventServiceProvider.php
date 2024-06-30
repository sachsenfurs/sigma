<?php

namespace App\Providers;

use App\Models\Ddas\ArtshowItem;
use App\Models\Post\Post;
use App\Models\SigAttendee;
use App\Models\SigEvent;
use App\Models\SigFavorite;
use App\Models\SigFilledForm;
use App\Models\SigReminder;
use App\Models\User;
use App\Models\UserRole;
use App\Models\TimetableEntry;
use App\Observers\ArtshowItemObserver;
use App\Observers\SigAttendeeObserver;
use App\Observers\SigEventObserver;
use App\Observers\SigFavoriteObserver;
use App\Observers\SigFilledFormObserver;
use App\Observers\SigReminderObserver;
use App\Observers\TimetableEntryObserver;
use App\Observers\UserObserver;
use App\Observers\UserRoleObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        //
    ];

    protected $observers = [
        //
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
