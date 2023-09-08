<?php

namespace App\Notifications\SigTimeslot;

use App\Models\SigTimeslot;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class SigTimeslotReminder extends Notification
{
    use Queueable;

    protected $sigTimeslot;

    /**
     * Create a new notification instance.
     *
     * @param SigTimeslot $fav
     * @return void
     */
    public function __construct(SigTimeslot $timeslot)
    {
        $this->sigTimeslot = $timeslot;
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
            ->line("your timeslot form the event " . $this->sigTimeslot->TimetableEntry->sigEvent->name . " starts in 15 Minutes!")
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
