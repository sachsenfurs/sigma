<?php

namespace App\Notifications\TimetableEntry;

use App\Models\TimetableEntry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class TimetableEntryReminder extends Notification
{
    use Queueable;

    protected $timetableEntry;

    /**
     * Create a new notification instance.
     *
     * @param SigFavorite $fav
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
        return ['telegram'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toTelegram($notifiable)
    {
        return TelegramMessage::create()
            ->to($notifiable->telegram_user_id)
            ->line("Hi " . $notifiable->name . ",")
            ->line("your favorite event " . $this->timetableEntry->sigEvent->name . " starts in 15 Minutes!")
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