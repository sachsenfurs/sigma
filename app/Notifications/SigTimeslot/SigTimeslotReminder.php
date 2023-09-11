<?php

namespace App\Notifications\SigTimeslot;

use App;
use App\Models\SigTimeslot;
use App\Notifications\SigFavorite\SigFavoriteReminder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class SigTimeslotReminder extends Notification
{
    use Queueable;

    protected $sigTimeslot;
    protected $reminder;

    /**
     * Create a new notification instance.
     *
     * @param SigTimeslot $fav
     * @return void
     */
    public function __construct(SigTimeslot $timeslot, SigFavoriteReminder $reminder)
    {
        $this->sigTimeslot = $timeslot;
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
        App::setLocale($notifiable->language);
        return TelegramMessage::create()
            ->to($notifiable->telegram_user_id)
            ->line(__("Hi ") . $notifiable->name . ",")
            ->line(__("your timeslot from the event ") . $this->sigTimeslot->timetableEntry->sigEvent->name . __(" starts in ")  . $this->reminder->minutes_before . __(" minutes!"))
            ->button(__("View Event") , route("public.timeslot-show", ['entry' => $this->sigTimeslot->id]));

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
