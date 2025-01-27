<?php

namespace App\Notifications\Sig;

use App\Models\SigTimeslot;
use App\Models\SigTimeslotReminder as SigTimeSlotReminderModel;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class SigTimeslotReminder extends Notification
{
    use Queueable;

    protected SigTimeslot $sigTimeslot;
    protected SigTimeSlotReminderModel $reminder;


    public function __construct(SigTimeslot $timeslot, SigTimeSlotReminderModel $reminder) {
        $this->sigTimeslot = $timeslot;
        $this->reminder = $reminder;
    }


    public function via(Model $notifiable): array {
        return NotificationService::channels($this, $notifiable);
    }

    public function toTelegram(Model $notifiable): MailMessage {
        return TelegramMessage::create()
            ->to($notifiable->telegram_user_id)
            ->line(__("Hi ") . $notifiable->name . ",")
            ->line(__('your booked timeslot of the event :event starts in :minutes_before minutes!', ["event" => $this->sigTimeslot->timetableEntry->sigEvent->name_localized, "minutes_before" => $this->reminder->minutes_before]))
            ->button(__("View Event") , route("timetable-entry.show", ['entry' => $this->sigTimeslot->id]));
    }


    public function toArray(Model $notifiable): array {
        return [
            'sigTimeslotId' => $this->sigTimeslot->id,
            'eventName' => $this->sigTimeslot->timetableEntry->sigEvent->name_localized,
            'minutes_before' => $this->reminder->minutes_before
        ];
    }
}
