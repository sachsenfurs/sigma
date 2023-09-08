<?php

namespace App\Notifications\SigFavorite;

use App\Models\SigFavorite;
use App\Models\SigReminder;
use App\Models\TimetableEntry;
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
        return ['telegram'];
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
            ->line("Hi " . $notifiable->name . ",")
            ->line("your favorite event " . $this->timetableEntry->sigEvent->name . " starts in "  . $this->reminder->minutes_before . " Minutes!")
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
