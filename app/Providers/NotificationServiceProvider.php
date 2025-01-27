<?php

namespace App\Providers;

use App\Notifications\Chat\NewChatMessage;
use App\Notifications\Sig\SigFavoriteReminder;
use App\Notifications\Sig\SigTimeslotReminder;
use App\Notifications\TimetableEntry\TimetableEntryCancelled;
use App\Notifications\TimetableEntry\TimetableEntryLocationChanged;
use App\Notifications\TimetableEntry\TimetableEntryTimeChanged;
use App\Services\NotificationService;
use App\Settings\ChatSettings;
use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void {

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void {
        /**
         * Notifications that will appear on the user settings page
         */
        NotificationService::$UserNotifications = [
            SigFavoriteReminder::class              => "sig_favorite",
            SigTimeslotReminder::class              => "sig_timeslot",
            TimetableEntryCancelled::class          => "timetable_entry_cancelled",
            TimetableEntryLocationChanged::class    => "timetable_entry_location_changed",
            TimetableEntryTimeChanged::class        => "timetable_entry_time_changed",
        ];

        if(app(ChatSettings::class)->enabled)
            NotificationService::$UserNotifications[NewChatMessage::class] = "new_chat_message";

    }
}
