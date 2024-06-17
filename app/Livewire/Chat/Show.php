<?php

namespace App\Livewire\Chat;

use App\Livewire\Sig\Forms\SigHostForm;
use App\Livewire\Traits\HasModal;
use App\Models\Chat;
use App\Models\Message;
use Livewire\Component;

class Show extends Component
{
    use HasModal;

    private $component = '';

    public $chat;

    public function createHost() {
        $this->form->reset();
        $this->form->name = auth()->user()->name;
        $this->showModal("hostModal");
    }

    public function storeMessage(Chat $chat, String $message)
    {
        $message = Message::create([
            'user_id' => auth()->id(),
            'content' => $message
        ]);
        Chat::find($chat->id)->attach($message->id);
    }

    public function render()
    {
        $messages = Chat::find($this->chat)->messages()->get();
        return view('lifewire.chat.show', [
            'component' => $this->component,
            'key' => random_int(-999, 999)
        ]);
    }

}
