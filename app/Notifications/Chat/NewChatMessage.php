<?php

namespace App\Notifications\Chat;

use App\Models\Message;
use App\Models\User;
use App\Notifications\Notification;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\Telegram\TelegramMessage;

class NewChatMessage extends Notification
{
    use Queueable;

    public function __construct(protected Message $message) {}

    public function via(User $notifiable): array {
        return NotificationService::channels($this, $notifiable, ['mail', 'database']);
    }

    public function toTelegram(User $notifiable): TelegramMessage {
        return TelegramMessage::create()
            ->to($notifiable->telegram_user_id)
            ->line(__("You have a new message in chat ({$this->message->chat->subject}):"))
            ->line("")
            ->line($this->message->text)
            ->button(__("Answer") , route("chats.index"));
    }

    public function toMail(User $notifiable): MailMessage {
        return (new MailMessage)
            ->subject(__("New Message in chat: {$this->message->chat->subject}"))
            ->greeting(__("Hello :name", ['name' => $notifiable->name]))
            ->line(__("You have a new message in chat ({$this->message->chat->subject}):"))
            ->line("")
            ->line($this->message->text)
            ->action(__("Answer") , route("chats.index"));
    }

    public function toArray(User $notifiable) {
        return [
            'text' => __("New Message in Chat")
        ];
    }

    /**
     * add a small delay so it won't spam the users mailbox when they is currently active
     */
    public function withDelay(Model $notifiable) {
        return [
            'mail' => now()->addMinutes(2),
            'telegram' => now()->addMinutes(2)
        ];
    }
}
