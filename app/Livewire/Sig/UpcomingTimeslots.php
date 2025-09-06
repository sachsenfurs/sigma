<?php

namespace App\Livewire\Sig;

use App\Livewire\Traits\HasModal;
use App\Models\SigAttendee;
use App\Models\SigTimeslot;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class UpcomingTimeslots extends Component
{
    use WithPagination, WithoutUrlPagination;
    use HasModal;
    protected $listeners = [
        'refresh' => '$refresh',
    ];

    public $selected_timeslot;
    public $selected_attendee;


    public function render() {
        $timeslots = auth()->user()->sigTimeslots()
            ->upcoming()
            ->with([
                'timetableEntry.sigLocation',
                'timetableEntry.sigEvent',
                'userReminder',
                'sigAttendees',
            ])
            ->orderBy("slot_start")->paginate(4, pageName: 'slots');

        return view('livewire.sig.upcoming-timeslots', [
            'timeslots' => $timeslots,
            'selected_timeslot' => $this->selected_timeslot?->load("sigAttendees.user"),
            'owner' => $this->selected_timeslot?->getOwner(),
        ]);
    }


    public function removeConfirmTimeslot(SigTimeslot $timeslot): void {
        $this->selected_timeslot = $timeslot;
        $this->showModal("removeConfirmModal");
    }

    public function removeTimeslot(): void {
        $attendees = auth()->user()->sigAttendees()->where("sig_timeslot_id", $this->selected_timeslot?->id)->get();
        foreach($attendees AS $attendee) {
            $this->authorize("delete", $attendee);
        }
        $attendees->each->delete(); // dont call "delete" on the eloquent query directly so the observer events can be dispatched
        $this->hideModal("removeConfirmModal");
    }


    public function showAttendeeModal(SigTimeslot $timeslot): void {
        $this->selected_timeslot = $timeslot;
        $this->showModal("attendeeModal");
    }

    public function confirmRemoveSigAttendee(SigAttendee $attendee): void {
        $this->selected_attendee = $attendee;
        $this->hideModal("attendeeModal");
        $this->showModal("removeAttendeeConfirmModal");
    }

    public function removeSigAttendee(): void {
        $this->authorize("delete", [$this->selected_attendee, $this->selected_timeslot?->getOwner()]);
        $this->selected_attendee?->delete();
        $this->hideModal("removeAttendeeConfirmModal");
        $this->showModal("attendeeModal");
    }

    public function showEditReminderModal(SigTimeslot $timeslot): void {
        $this->selected_timeslot = $timeslot;
        $this->showModal("editReminderModal");
    }
}
