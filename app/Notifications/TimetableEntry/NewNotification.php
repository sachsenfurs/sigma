<?php

namespace App\Notifications\TimetableEntry;

use App\Models\TimetableEntry;
use App\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected TimetableEntry $entry) {}


    public static function getName(): string {
        return __("New Event in Schedule");
    }

    protected function getSubject(): ?string {
        return "🆕 " . __("New Event in Schedule") . ": " . $this->entry->sigEvent->name_localized;
    }

    protected function getLines(): array {
        return [
            "🕗 " . __("When?") . " " . $this->entry->start->translatedFormat("l, H:i") . " (" . $this->entry->formatted_length . ")",
            "📍 " . __("Where?") . " " . $this->entry->sigLocation->name_localized,
        ];
    }

    protected function getAction(): ?string {
        return __("View");
    }

    protected function getActionUrl(): string {
        return route("timetable-entry.show", $this->entry);
    }
}
