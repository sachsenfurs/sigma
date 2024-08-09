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

class TimetableEntryCancelled extends Notification
{
    use Queueable;

    protected $timetableEntry;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(TimetableEntry $timetableEntry) {
        $this->timetableEntry = $timetableEntry;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable) {
        //return UserNotificationChannel::where('user_id', $notifiable->id)->where('notification', 'timetable_entry_cancelled')->first()->channel;
        return UserNotificationChannel::list('timetable_entry_cancelled', $notifiable->id, ['mail']);
    }

    /**
     * Get the Telegram representation of the notification.
     *
     * @param mixed $notifiable
     * @return \NotificationChannels\Telegram\TelegramMessage;
     */
    public function toTelegram($notifiable): TelegramMessage {
        App::setLocale($notifiable->language);
        return TelegramMessage::create()
                              ->to($notifiable->telegram_user_id)
                              ->line('[INFO]')
                              ->line(__('the event ') . $this->timetableEntry->sigEvent->name_localized . __(' was cancelled!'))
                              ->button(__('View Event'), route('timetable-entry.show', ['entry' => $this->timetableEntry->id]));
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage {
        App::setLocale($notifiable->language);
        return (new MailMessage)
            ->subject('[INFO] ' . __('the event ') . $this->timetableEntry->sigEvent->name_localized . __(' was cancelled!'))
            ->line('[INFO]')
            ->line(__('The event :event was cancelled!', $this->timetableEntry->sigEvent->name_localized))
            ->action(__('View Event'), route('public.timeslot-show', ['entry' => $this->timetableEntry->id]));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable) {
        return [
            'type' => 'timetable_entry_cancelled',
            'timetableEntryId' => $this->timetableEntry->id,
            'eventName' => $this->timetableEntry->sigEvent->name_localized
        ];
    }
}
