<?php

namespace App\Notifications\TimetableEntry;

use App\Models\TimetableEntry;
use App\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class CancelledNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected TimetableEntry $entry) {}


    public static function getName(): string {
        return __("Event Cancelled");
    }

    protected function getSubject(): ?string {
        return "❌ " . __("Event Cancelled") . ": " . $this->entry->sigEvent->name_localized;
    }

    protected function getLines(): array {
        return [
            __("Originally scheduled: :date", ['date' => $this->entry->start->translatedFormat("l, H:i")]),
        ];
    }

    protected function getAction(): ?string {
        return __("View");
    }

    protected function getActionUrl(): string {
        return route("timetable-entry.show", $this->entry);
    }
}
