<?php

namespace App\Notifications\SigFavorite;

use App;
use App\Models\SigFavorite;
use App\Models\SigReminder;
use App\Models\TimetableEntry;
use App\Models\UserNotificationChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class SigFavoriteReminder extends Notification
{
    use Queueable;

    protected $timetableEntry;
    protected $reminder;

    /**
     * Create a new notification instance.
     *
     * @param TimetableEntry $entry
     * @param SigFavorite $fav
     * @return void
     */
    public function __construct(TimetableEntry $entry, SigReminder $reminder)
    {
        $this->timetableEntry = $entry;
        $this->reminder = $reminder;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return UserNotificationChannel::list('sig_favorite_reminder', $notifiable->id, 'mail');
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return NotificationChannels\Telegram\TelegramMessage
     */
    public function toTelegram($notifiable)
    {
        App::setLocale($notifiable->language);
        return TelegramMessage::create()
            ->to($notifiable->telegram_user_id)
            ->line(__("Hi ") . $notifiable->name . ",")
            ->line(__('your favorite event :event starts in :minutes_before minutes!', ["event" => $this->timetableEntry->sigEvent->name_localized, "minutes_before" => $this->reminder->minutes_before]))
            ->button(__("View Event") , route("timetable-entry.show", ['entry' => $this->timetableEntry]));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'type' => 'sig_favorite_reminder',
            'timetableEntryid' => $this->timetableEntry->id,
            'eventName' => $this->timetableEntry->sigEvent->name_localized,
            'minutes_before' => $this->reminder->minutes_before
        ];
    }
}
