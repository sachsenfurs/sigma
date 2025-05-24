<?php

namespace App\Notifications\TimetableEntry;

use App\Models\TimetableEntry;
use App\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;

class ChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected TimetableEntry $entry, protected Collection $originalData) {}


    public static function getName(): string {
        return __("Event Changed");
    }

    protected function getSubject(): ?string {
        return "❗ " . __("Event Changed") . ": " . $this->entry->sigEvent->name_localized;
    }

    protected function getLines(): array {
        return $this->originalData->map(function($oldValue, $oldKey) {
            return match($oldKey) {
                "start" => "🕗 " . __("Start time changed from :old to :new.", [
                    'old' => $oldValue->translatedFormat((fn() => $oldValue->isSameDay($this->entry->start) ? "H:i" : "l, H:i")()),
                    'new' => $this->entry->start->translatedFormat((fn() => $oldValue->isSameDay($this->entry->start) ? "H:i" : "l, H:i")())
                ]),
                "sig_location_id" => "📍 " . __("Location changed to: :new.", [
                    'new' => $this->entry->sigLocation->name_localized,
                ]),
                default => "",
            };
        })->toArray();
    }

    protected function getAction(): ?string {
        return __("View");
    }

    protected function getActionUrl(): string {
        return route("timetable-entry.show", $this->entry);
    }

    public function shouldSend(): bool {
        // only send when lines not empty
        return !collect($this->getLines())->filter(fn($e) => !empty($e))->isEmpty();
    }
}
