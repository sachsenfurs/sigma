<?php

namespace App\Notifications\SigTimeslot;

use App;
use App\Models\SigTimeslot;
use App\Models\SigFavoriteReminder;
use App\Models\SigTimeslotReminder as SigTimeSlotReminderModel;
use App\Models\UserNotificationChannel;
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
    public function __construct(SigTimeslot $timeslot, SigTimeSlotReminderModel $reminder)
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
        //return UserNotificationChannel::where('user_id', $notifiable->id)->where('notification', 'sig_timeslot_reminder')->first()->channel;
        return UserNotificationChannel::list('sig_timeslot_reminder', $notifiable->id, ['telegram']);
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
            ->line(__('your booked timeslot of the event :event starts in :minutes_before minutes!', ["event" => $this->sigTimeslot->timetableEntry->sigEvent->name_localized, "minutes_before" => $this->reminder->minutes_before]))
            ->button(__("View Event") , route("timetable-entry.show", ['entry' => $this->sigTimeslot->id]));

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
            'type' => 'sig_timeslot_reminder',
            'sigTimeslotId' => $this->sigTimeslot->id,
            'eventName' => $this->sigTimeslot->timetableEntry->sigEvent->name_localized,
            'minutes_before' => $this->reminder->minutes_before
        ];
    }
}
