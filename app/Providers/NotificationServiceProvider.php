<?php

namespace App\Providers;

use App\Facades\NotificationService;
use App\Notifications\Chat\NewChatMessageNotification;
use App\Notifications\Ddas\ArtshowWinnerNotification;
use App\Notifications\MorphedDatabaseChannel;
use App\Notifications\Sig\NewSigApplicationNotification;
use App\Notifications\Sig\SigFavoriteReminder;
use App\Notifications\Sig\SigTimeslotReminder;
use App\Notifications\TimetableEntry\TimetableEntryCancelled;
use App\Notifications\TimetableEntry\TimetableEntryLocationChanged;
use App\Notifications\TimetableEntry\TimetableEntryTimeChanged;
use App\Settings\ChatSettings;
use Illuminate\Database\QueryException;
use Illuminate\Notifications\Channels\DatabaseChannel;
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

        $this->app->singleton(\App\Services\NotificationService::class, function () {
            return new \App\Services\NotificationService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void {
        /**
         * Notifications that will appear on the user settings page
         * also used for database morphing to keep consistency
         *
         * static property $userSetting from the extended Notification class can be used to disable it for the user settings page
         */
        NotificationService::registerUserNotifications([
            SigFavoriteReminder::class              => "sig_favorite",
            SigTimeslotReminder::class              => "sig_timeslot",
            TimetableEntryCancelled::class          => "timetable_entry_cancelled",
            TimetableEntryLocationChanged::class    => "timetable_entry_location_changed",
            TimetableEntryTimeChanged::class        => "timetable_entry_time_changed",
            ArtshowWinnerNotification::class        => "artshow_winner_notification",
        ]);
        try {
            if(app(ChatSettings::class)->enabled)
                NotificationService::registerUserNotifications([NewChatMessageNotification::class => "new_chat_message"]);
        } catch(QueryException $e) {
            // settings not present (migration/setup in progress?)
        }

        NotificationService::registerRoutableNotifications([
            NewSigApplicationNotification::class    => "new_sig_application",
        ]);
    }
}
