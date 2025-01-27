<?php

namespace App\Livewire\Chat;

use App\Livewire\Traits\HasModal;
use App\Models\Chat;
use App\Models\UserRole;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ChatComponent extends Component
{
    use HasModal;

    public $text;

    public ?Chat $currentChat;

    /**
     * vars for createNewChat modal
     */
    public ?string $department;
    public ?string $subject;

    public function render() {
        $chats = auth()->user()->chats()->with(["userRole", "messages"])->get();
        $this->dispatch("scrolldown");
        return view('livewire.chat.chat-component', compact("chats"));
    }

    public function submitMessage(): void {
        if($this->currentChat) {
            $this->authorize("view", $this->currentChat);
            $this->validate([
                'text' => "required|min:2"
            ]);
            $this->currentChat->messages()->create([
                'user_id' => auth()->id(),
                'text' => $this->text,
            ]);
        }
        $this->dispatch("scrolldown");
        $this->reset(['text']);
    }

    public function selectChat($chatId): void {
        $chat = auth()->user()->chats()->with(["messages", "messages.user"])->find($chatId);
        $this->authorize("view", $chat);
        $this->currentChat = $chat;
        $chat->markAsRead();
    }

    public function newChatModal(): void {
        $this->showModal();
    }

    public function createNewChat() {
        $this->authorize("create", Chat::class);
        $validated = $this->validate([
            'department' => Rule::in(UserRole::chattable()->pluck("id")),
            'subject' => "string|required|min:3",
        ]);
        auth()->user()->chats()->create([
            'user_role_id' => $validated['department'],
            'subject' => $validated['subject'],
        ]);
        $this->reset();
        $this->hideModal();
    }
}
