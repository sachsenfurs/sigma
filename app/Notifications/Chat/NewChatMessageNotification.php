<?php

namespace App\Notifications\Chat;

use App\Filament\Resources\ChatResource;
use App\Models\Message;
use App\Notifications\Notification;
use App\Facades\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\Telegram\TelegramMessage;

class NewChatMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected Message $message) {}

    protected function getSubject(): string { // can't be called in constructor, otherwise the localization won't be applied
        return __("New Message from :user (:chat)", ['user' => $this->message->user->name, 'chat' => $this->message->chat->subject]);
    }

    protected function getVia(): array {
        return ['mail'];
    }

    public function toTelegram(object $notifiable): TelegramMessage {
        return TelegramMessage::create()
            ->line($this->getSubject() . ":")
            ->line("")
            ->line($this->message->text)
            ->button(__("Answer") , route("chats.index"));
    }

    public function toMail(object $notifiable): Renderable {
        return (new MailMessage)
            ->subject($this->getSubject())
            ->greeting(__("Hello :name", ['name' => $notifiable->name]))
            ->line($this->getSubject() . ":")
            ->markdown("mail.new-chat-message", ['messages' => $this->message->chat->messages()->from($this->message->user)->unread()->get(), 'user' => $notifiable ])
            ->action(__("Answer"), $this->message->user_id != $this->message->chat->user_id ? route("chats.index") : ChatResource::getUrl('edit', ['record' => $this->message->chat]));
    }

    public function toArray(object $notifiable): array {
        return [
            'text' => __("New Message in Chat")
        ];
    }

    /**
     * add a small delay so it won't spam the users mailbox when they is currently active
     */
    public function withDelay(object $notifiable): array {
        return [
            'mail' => now()->addMinutes(5),
            'telegram' => now()->addMinutes(5)
        ];
    }

    public function shouldSend(): bool {
        return $this->message->read_at == null  // message is still unread
            AND $this->message->chat->messages()->where("created_at", ">", $this->message->created_at)->count() == 0; // no messages were sent after
    }

}
