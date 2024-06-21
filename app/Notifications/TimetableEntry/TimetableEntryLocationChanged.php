<?php

namespace App\Notifications\TimetableEntry;

use App;
use App\Models\TimetableEntry;
use App\Models\UserNotificationChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class TimetableEntryLocationChanged extends Notification
{
    use Queueable;

    protected $timetableEntry;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(TimetableEntry $timetableEntry)
    {
        $this->timetableEntry = $timetableEntry;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return UserNotificationChannel::where('user_id', $notifiable->id)->where('notification', 'timetable_entry_location_changed')->first()->channel;
    }

    /**
     * Get the Telegram representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \NotificationChannels\Telegram\TelegramMessage;
     */
    public function toTelegram($notifiable)
    {
        App::setLocale($notifiable->language);
        return TelegramMessage::create()
            ->to($notifiable->telegram_user_id)
            ->line(__('[CHANGE]'))
            ->line(__('The location for the event ') . $this->timetableEntry->sigEvent->name_localized . ' has changed!')
            ->line(__('New location: ') . $this->timetableEntry->sigLocation->name)
            ->button(__('View Event'), route('timetable-entry.show', ['entry' => $this->timetableEntry->id]));
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
            //
        ];
    }
}
