<?php

namespace App\Notifications\Sig;

use App\Models\SigTimeslot;
use App\Notifications\Notification;
use Illuminate\Bus\Queueable;

class SigTimeslotReminder extends Notification
{
    use Queueable;

    public function __construct(protected SigTimeslot $sigTimeslot) {}

    public static function getName(): string {
        return __("Time slot reminder");
    }

    // enforced channels
    protected function getVia(): array {
        return ['database'];
    }

    protected function getSubject(): ?string {
        return __("Timeslot :time for :event starts in :min Minutes!", [
            'time' => $this->sigTimeslot->slot_start->translatedFormat("H:i"),
            'event' => $this->sigTimeslot->timetableEntry->sigEvent->name_localized,
            'min' => (int) round($this->sigTimeslot->slot_start->diffInMinutes()*-1)
        ]);
    }

    protected function getLines(): array {
        return [
            "🕗 **" . __("Time") . ":** " . $this->sigTimeslot->slot_start->translatedFormat("H:i") . " - " . $this->sigTimeslot->slot_end->translatedFormat("H:i"),
            "📍 **" . __("Location") . ":" . $this->sigTimeslot->timetableEntry->sigLocation->name_localized,
        ];
    }

    protected function getAction(): ?string {
        return __("View Timeslot");
    }

    protected function getActionUrl(): string {
        return route("timetable-entry.show", $this->sigTimeslot->timetableEntry);
    }

    public function toArray(object $notifiable): array {
        return [
            //
        ];
    }
}
