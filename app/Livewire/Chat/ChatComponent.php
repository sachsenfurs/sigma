<?php

namespace App\Livewire\Chat;

use App\Livewire\Traits\HasModal;
use App\Models\Chat;
use App\Models\UserRole;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ChatComponent extends Component
{
    use HasModal;

    public $text;

    public $currentChat = null;

    /**
     * vars for createNewChat modal
     */
    public $departments;
    public ?string $subject;

    public function mount(): void {
        $this->departments = Cache::remember("chattableUserRoles", 300, fn() => UserRole::chattable()->get());
    }

    public function render() {
        $chats = auth()->user()->chats()->get();
        return view('livewire.chat.chat-component', compact("chats"));
    }

    public function submitMessage(): void {
        if($this->currentChat) {
            $this->authorize("view", $this->currentChat);
            $this->validate([
                'text' => "required|min:2|max:4000"
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
        $this->markAsRead();
        $this->dispatch("scrolldown");
    }

    public function markAsRead(): void {
        $this->currentChat?->markAsRead();
    }

    public function newChatModal(): void {
        $this->showModal();
    }

    public function createNewChat() {
        $this->authorize("create", Chat::class);
        $validated = $this->validate([
            'department' => ['required', Rule::in(UserRole::chattable()->pluck("id"))],
            'subject' => "string|required|min:3|max:40",
        ]);
        auth()->user()->chats()->create([
            'user_role_id' => $validated['department'],
            'subject' => $validated['subject'],
        ]);
        $this->reset();
        $this->hideModal();
    }
}
