<?php

namespace App\Notifications\TimetableEntry;

use App\Models\TimetableEntry;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class TimetableEntryCancelled extends Notification
{
    use Queueable;

    protected TimetableEntry $timetableEntry;

    public function __construct(TimetableEntry $timetableEntry) {
        $this->timetableEntry = $timetableEntry;
    }

    public function via($notifiable): array {
        return NotificationService::channels($this, $notifiable);
    }

    public function toTelegram($notifiable): TelegramMessage {
        return TelegramMessage::create()
            ->to($notifiable->telegram_user_id)
            ->line('[INFO]')
            ->line(__('the event ') . $this->timetableEntry->sigEvent->name_localized . __(' was cancelled!'))
            ->button(__('View Event'), route('timetable-entry.show', ['entry' => $this->timetableEntry->id]));
    }


    public function toMail(object $notifiable): MailMessage {
        return (new MailMessage)
            ->subject('[INFO] ' . __('the event ') . $this->timetableEntry->sigEvent->name_localized . __(' was cancelled!'))
            ->line('[INFO]')
            ->line(__('The event :event was cancelled!', $this->timetableEntry->sigEvent->name_localized))
            ->action(__('View Event'), route('public.timeslot-show', ['entry' => $this->timetableEntry->id]));
    }


    public function toArray($notifiable): array {
        return [
            'timetableEntryId' => $this->timetableEntry->id,
            'eventName' => $this->timetableEntry->sigEvent->name_localized
        ];
    }
}
