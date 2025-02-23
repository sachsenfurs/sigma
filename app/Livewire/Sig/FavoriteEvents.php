<?php

namespace App\Livewire\Sig;

use App\Models\SigFavorite;
use App\Models\User;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class FavoriteEvents extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $minutes = [];

    private function getFavs() {
        $favs = auth()->user()->favorites()
             ->upcoming()
             ->with(["timetableEntry.sigEvent", "timetableEntry.sigLocation", "reminders"])
             ->withAggregate("timetableEntry","start")
             ->orderBy("timetable_entry_start")
             ->paginate(12, pageName: "favs");
        $this->minutes = $favs->mapWithKeys(fn($fav) => [$fav->id => $fav->reminders?->first()?->offset_minutes ]);
        return $favs;
    }

    public function render() {
        return view('livewire.sig.favorite-events', [
            'favorites' => $this->getFavs(),
        ]);
    }

    public function setReminderTime(SigFavorite $favorite) {
        $validated = $this->validate([
            "minutes.{$favorite->id}" => 'int',
        ]);

        if($reminder = $favorite->reminders()->whereHasMorph("notifiable", User::class, fn($query) => $query->where("notifiable_id", auth()->id()))->first())
            $reminder->update(['offset_minutes' => $this->minutes[$favorite->id]]);
        else
            $favorite->reminders()->make(['offset_minutes' => $this->minutes[$favorite->id]])->notifiable()->associate(auth()->user())->save();
    }
}
