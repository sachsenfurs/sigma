<?php

namespace App\Providers;

use App\Facades\NotificationService;
use App\Notifications\Chat\NewChatMessageNotification;
use App\Notifications\Ddas\ArtshowItemOutbidNotification;
use App\Notifications\Ddas\ArtshowWinnerNotification;
use App\Notifications\Ddas\ProcessedDealerNotification;
use App\Notifications\Ddas\ProcessedItemNotification;
use App\Notifications\Ddas\SubmittedItemNotification;
use App\Notifications\Messages\MessageNotification;
use App\Notifications\MorphedDatabaseChannel;
use App\Notifications\Shift\ShiftReminder;
use App\Notifications\Shift\ShiftSummaryReminder;
use App\Notifications\Sig\NewApplicationNotification;
use App\Notifications\Sig\ProcessedApplicationNotification;
use App\Notifications\Sig\SigFavoriteReminder;
use App\Notifications\Sig\SigTimeslotReminder;
use App\Notifications\TimetableEntry\CancelledFavoriteNotification;
use App\Notifications\TimetableEntry\CancelledNotification;
use App\Notifications\TimetableEntry\ChangedFavoriteNotification;
use App\Notifications\TimetableEntry\ChangedNotification;
use App\Notifications\TimetableEntry\NewNotification;
use App\Notifications\TimetableEntry\TimetableEntryReminder;
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
            CancelledFavoriteNotification::class        => "favorite_event_cancelled",
            ChangedFavoriteNotification::class          => "favorite_event_changed",
            SigFavoriteReminder::class                  => "sig_favorite_reminder",
            SigTimeslotReminder::class                  => "sig_timeslot_reminder",
            ArtshowItemOutbidNotification::class        => "artshow_item_outbid_notification",
            ShiftReminder::class                        => "shift_reminder",
            ShiftSummaryReminder::class                 => "shift_summary",
        ]);

        try {
            if(app(ChatSettings::class)->enabled)
                NotificationService::registerUserNotifications([NewChatMessageNotification::class => "new_chat_message"]);
        } catch(QueryException $e) {
            // settings not present (migration/setup in progress?)
        }

        NotificationService::registerAdminNotifications([
            ProcessedApplicationNotification::class     => "sig_application_processed",
            ArtshowWinnerNotification::class            => "artshow_winner_notification",
            ProcessedItemNotification::class            => "artshow_item_processed",
            ProcessedDealerNotification::class          => "dealer_processed",
            TimetableEntryReminder::class               => "timetable_entry_reminder",
            MessageNotification::class                  => "message_notification",
        ]);

        NotificationService::registerRoutableNotifications([
            NewApplicationNotification::class           => "sig_application_new",
            SubmittedItemNotification::class            => "artshow_item_submitted",
            NewNotification::class                      => "event_new",
            CancelledNotification::class                => "event_cancelled",
            ChangedNotification::class                  => "event_changed",
        ]);
    }
}
