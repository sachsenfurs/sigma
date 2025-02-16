<?php

namespace App\Providers;

use App\Observers\ArtshowItemObserver;
use App\Observers\SigEventObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     */
    protected $listen = [
        //
    ];

    protected $observers = [
        //
    ];

    public function boot(): void {
        /*
         * using the already existing model observer to subscribe to custom events
         */
        Event::subscribe(SigEventObserver::class);
        Event::subscribe(ArtshowItemObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool {
        return false;
    }
}
