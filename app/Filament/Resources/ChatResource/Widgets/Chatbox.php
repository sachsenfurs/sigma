<?php

namespace App\Filament\Resources\ChatResource\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\On;

class Chatbox extends Widget
{
    public ?Model $record = null;
    public string $text;
    protected int | string | array $columnSpan = 'full';
    protected static string $view = "filament.resources.chats.pages.view-chat";

//    protected $listeners = [
//        '$refresh',
//        'refresh',
//    ];

    public function mount() {
        $this->record->messages->load("user");
    }

    #[On('refresh')]
    public function refresh() {}

    public function sendMessage(): void {
        if($this->record) {
            $this->authorize("view", $this->record);
            $this->validate([
                'text' => "required|min:2|max:4000"
            ]);
            $this->record->messages()->create([
                'user_id' => auth()->id(),
                'text' => $this->text,
            ]);
        }
        $this->dispatch("scrolldown");
        $this->reset(['text']);
    }
}
