<?php

namespace App\Providers;

use App\Notifications\Chat\NewChatMessageNotification;
use App\Notifications\Ddas\ArtshowWinnerNotification;
use App\Notifications\MorphedDatabaseChannel;
use App\Notifications\Sig\SigFavoriteReminder;
use App\Notifications\Sig\SigTimeslotReminder;
use App\Notifications\TimetableEntry\TimetableEntryCancelled;
use App\Notifications\TimetableEntry\TimetableEntryLocationChanged;
use App\Notifications\TimetableEntry\TimetableEntryTimeChanged;
use App\Services\NotificationService;
use App\Settings\ChatSettings;
use Illuminate\Notifications\Channels\DatabaseChannel;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void {
        /**
         * "morph map" when writing to database
         */
        $this->app->bind(DatabaseChannel::class, MorphedDatabaseChannel::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void {
        /**
         * Notifications that will appear on the user settings page
         *
         * also used for database morphing to keep consistency
         */
        NotificationService::registerNotification([
            SigFavoriteReminder::class              => "sig_favorite",
            SigTimeslotReminder::class              => "sig_timeslot",
            TimetableEntryCancelled::class          => "timetable_entry_cancelled",
            TimetableEntryLocationChanged::class    => "timetable_entry_location_changed",
            TimetableEntryTimeChanged::class        => "timetable_entry_time_changed",
            ArtshowWinnerNotification::class        => "artshow_winner_notification",
        ]);

        if(app(ChatSettings::class)->enabled)
            NotificationService::registerNotification([NewChatMessageNotification::class => "new_chat_message"]);
    }
}
