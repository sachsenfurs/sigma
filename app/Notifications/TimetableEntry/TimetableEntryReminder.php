<?php

namespace App\Notifications\TimetableEntry;

use App\Models\TimetableEntry;
use App\Notifications\Notification;
use Illuminate\Bus\Queueable;

class TimetableEntryReminder extends Notification
{
    use Queueable;

    public static function getName(): string {
        return __("Reminder for events");
    }

    public function __construct(protected TimetableEntry $entry) {}

    // enforced channels
    protected function getVia(): array {
        return ['database'];
    }

    protected function getSubject(): ?string {
        return __(":sig starts in :min minutes!", [
            'sig' => $this->entry->sigEvent->name_localized,
            'min' => $this->getMinutesDiff(),
        ]);
    }

    protected function getLines(): array {
        return [
            "🕗 " . __("Time") . ": " . $this->entry->start->translatedFormat("H:i") . " - " . $this->entry->end->translatedFormat("H:i"),
            "📍 " . __("Location") . ": " . $this->entry->sigLocation->name_localized,
        ];
    }

    protected function getAction(): ?string {
        return __("Show Event");
    }

    protected function getActionUrl(): string {
        return route("timetable-entry.show", $this->entry);
    }

    protected function getMinutesDiff(): int {
        return (int) round($this->entry->start->diffInMinutes()*-1);
    }

}
