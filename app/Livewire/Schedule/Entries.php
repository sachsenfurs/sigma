<?php

namespace App\Livewire\Schedule;

use App\Livewire\Traits\HasModal;
use App\Models\SigAttendee;
use App\Models\SigTimeslot;
use App\Models\TimetableEntry;
use Livewire\Component;

class Entries extends Component
{
    use HasModal;

    public TimetableEntry $entry;

    public ?int $registerSlotId = null;

    public function registerSlot(int $slotId): void {
        $this->registerSlotId = $slotId;
        $this->showModal('registerModal');
    }

    public function registerConfirm(): void {
        $timeslot = SigTimeslot::findOrFail($this->registerSlotId);
        $this->authorize("create", [SigAttendee::class, $timeslot]);

        $timeslot->sigAttendees()->updateOrCreate([
            'user_id' => auth()->id()
        ]);

        $this->registerSlotId = null;
        $this->hideModal('registerModal');
    }

    public function unregisterSlot(int $slotId): void {
        $this->registerSlotId = $slotId;
        $this->showModal('unregisterModal');
    }

    public function unregisterConfirm(): void {
        $timeslot = SigTimeslot::findOrFail($this->registerSlotId);
        $sigAttendee = $timeslot->sigAttendees->where("user_id", auth()->id())->first();
        $this->authorize("delete", $sigAttendee);
        $sigAttendee->delete();
        $this->hideModal('unregisterModal');
    }

    public function toggleFavorite(int $entryId): void {
        if(!auth()->check())
            return;

        $existing = auth()->user()->favorites()->where("timetable_entry_id", $entryId)->first();
        if($existing)
            $existing->delete();
        else
            auth()->user()->favorites()->create([
                'timetable_entry_id' => $entryId,
            ]);
    }


}
