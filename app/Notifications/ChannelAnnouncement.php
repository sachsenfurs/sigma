<?php

namespace App\Notifications;

use App\Models\UserNotificationChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class ChannelAnnouncement extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return UserNotificationChannel::list('sig_timeslot_reminder', $notifiable->id, ['telegram', 'email']);
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return NotificationChannels\Telegram\TelegramMessage
     */
    public function toTelegram($notifiable)
    {
        return TelegramMessage::create()
            ->to($notifiable->telegram_user_id)
            ->line("The event XXX is starting soon!")
            //->button('View Event', route("public.timeslot-show", ['entry' => $this->timetableEntry->id]));
            ->button('View Event', 'https://sigma.staging.sachsenfurs.de/show/2');
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
