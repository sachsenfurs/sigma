<?php

namespace App\Notifications\TimetableEntry;

use App\Models\TimetableEntry;
use App\Facades\NotificationService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class TimetableEntryTimeChanged extends Notification
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
            ->line(__('The time of the event :event has changed!', ["event" => $this->timetableEntry->sigEvent->name_localized]))
            ->line(__('New Time: ') . Carbon::parse($this->timetableEntry->start)->format("H:i") . ' - ' . Carbon::parse($this->timetableEntry->end)->format("H:i"))
            ->button(__('View Event'), route('timetable-entry.show', ['entry' => $this->timetableEntry->id]));
    }

    public function toArray($notifiable): array {
        return [
            'timetableEntryId' => $this->timetableEntry->id,
            'eventName' => $this->timetableEntry->sigEvent->name_localized,
            'newStartTime' => $this->timetableEntry->start,
            'newEndTime' => $this->timetableEntry->end
        ];
    }
}
