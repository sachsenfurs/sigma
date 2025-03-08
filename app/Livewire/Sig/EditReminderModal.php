<?php

namespace App\Livewire\Sig;

use App\Livewire\Traits\HasModal;
use App\Models\User;
use Livewire\Attributes\Modelable;
use Livewire\Component;

class EditReminderModal extends Component
{
    use HasModal;

    #[Modelable]
    public $remindable;

    public $time = 15; // default

    public function boot() {
        $this->time = $this->remindable?->reminders()?->first()?->offset_minutes ?? 15;
    }

    public function updateReminder(): void {
        $this->validate([
            'time' => 'int|min:0|max:240',
        ]);

        $reminder = $this->remindable->reminders()->whereHasMorph("notifiable", User::class, fn($query) => $query->where("notifiable_id", auth()->id()))->first();
        if($reminder) {
            $reminder->update([
                'offset_minutes' => $this->time,
            ]);
        } else {
            $e = auth()->user()->reminders()->make([
                'offset_minutes' => $this->time,
            ]);
            $e->remindable()->associate($this->remindable);
            $e->save();
        }
        $this->hideModal("editReminderModal");
        $this->dispatch('refresh');
    }

    public function render() {
        return view('livewire.sig.edit-reminder-modal');
    }
}
