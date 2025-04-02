<?php

namespace App\Providers;

use App\Models\Ddas\ArtshowItem;
use App\Models\Ddas\Dealer;
use App\Models\Post\PostChannel;
use App\Models\SigEvent;
use App\Models\SigFavorite;
use App\Models\SigTimeslot;
use App\Models\TimetableEntry;
use App\Models\User;
use App\Models\UserRole;
use App\Services\Translator;
use App\Settings\AppSettings;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {
        if($this->app->environment('production')) {
            \URL::forceScheme('https');
        } else {
            $this->app->register(FakerServiceProvider::class);
        }

        // registering in boot because we are using values from settings (database)
        $this->app->singleton(Translator::class, function() {
            return new Translator(app(AppSettings::class)->deepl_api_key, app(AppSettings::class)->deepl_source_lang, app(AppSettings::class)->deepl_target_lang);
        });

        Paginator::useBootstrapFive();

        Relation::morphMap([
            'user'              => User::class, // getMorphClass() on User::class won't work with filament!
            'post_channel'      => PostChannel::class,
            'user_role'         => UserRole::class,
            'dealer'            => Dealer::class,
            'artshow_item'      => ArtshowItem::class,
            'sig_event'         => SigEvent::class,
            'sig_timeslot'      => SigTimeslot::class,
            'timetable_entry'   => TimetableEntry::class,
            'sig_favorite'      => SigFavorite::class,
        ]);

        /**
         * defining global scopes
         */
        if(!auth()->user()?->isAdmin()) {
            TimetableEntry::addGlobalScope('private', function(Builder $query) {
                $query->whereHas("sigEvent", SigEvent::applyPrivateScope());
            });
            SigEvent::addGlobalScope('private', SigEvent::applyPrivateScope());
        }
    }
}
