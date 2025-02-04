<?php

namespace App\Notifications\Chat;

use App\Filament\Resources\ChatResource;
use App\Models\Message;
use App\Notifications\Notification;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\Telegram\TelegramMessage;

class NewChatMessage extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected Message $message) {}

    private function getSubject(): string { // can't be called in constructor, otherwise the localization won't be applied
        return __("New Message from :user (:chat)", ['user' => $this->message->user->name, 'chat' => $this->message->chat->subject]);
    }

    public function via(object $notifiable): array {
        return NotificationService::channels($this, $notifiable, ['mail']);
    }

    public function toTelegram(object $notifiable): TelegramMessage {
        return TelegramMessage::create()
            ->to($notifiable->telegram_user_id)
            ->line($this->getSubject() . ":")
            ->line("")
            ->line($this->message->text)
            ->button(__("Answer") , route("chats.index"));
    }

    public function toMail(object $notifiable): MailMessage {
        return (new MailMessage)
            ->subject($this->getSubject())
            ->greeting(__("Hello :name", ['name' => $notifiable->name]))
            ->line($this->getSubject() . ":")
            ->view("mail::html.new-chat-message", ['messages' => $this->message->chat->messages()->from($this->message->user)->unread()->get()])
            ->action(__("Answer"), $this->message->user_id != $this->message->chat->user_id ? route("chats.index") : ChatResource::getUrl('edit', ['record' => $this->message->chat]));
    }

    public function toArray(object $notifiable) {
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

    public function shouldSend() {
        return $this->message->read_at == null  // message is still unread
            AND $this->message->chat->messages()->where("created_at", ">", $this->message->created_at)->count() == 1; // no messages were sent after
    }

}
