<?php

namespace App\Notifications\TimetableEntry;

use App\Models\TimetableEntry;
use App\Facades\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class TimetableEntryLocationChanged extends Notification
{
    use Queueable;

    protected TimetableEntry $timetableEntry;

    public function __construct(TimetableEntry $timetableEntry) {
        $this->timetableEntry = $timetableEntry;
    }

    public function via($notifiable): array {
        return NotificationService::channels($this, $notifiable);
    }

    public function toTelegram($notifiable) {
        return TelegramMessage::create()
            ->line(__('[CHANGE]'))
            ->line(__('The location for the event :event has changed!', ["event" => $this->timetableEntry->sigEvent->name_localized]))
            ->line(__('New location: ') . $this->timetableEntry->sigLocation->name)
            ->button(__('View Event'), route('timetable-entry.show', ['entry' => $this->timetableEntry->id]));
    }

    public function toArray($notifiable) {
        return [
            'timetableEntryId' => $this->timetableEntry->id,
            'eventName' => $this->timetableEntry->sigEvent->name_localized,
            'newLocation' => $this->timetableEntry->sigLocation->name
        ];
    }
}
