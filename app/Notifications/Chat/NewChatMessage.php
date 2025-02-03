<?php

namespace App\Notifications\Chat;

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

    public function via(object $notifiable): array {
        return NotificationService::channels($this, $notifiable, ['mail']);
    }

    public function toTelegram(object $notifiable): TelegramMessage {
        return TelegramMessage::create()
            ->to($notifiable->telegram_user_id)
            ->line(__("New message from {$this->message->user->name} in chat ({$this->message->chat->subject}):"))
            ->line("")
            ->line($this->message->text)
            ->button(__("Answer") , route("chats.index"));
    }

    public function toMail(object $notifiable): MailMessage {
        return (new MailMessage)
            ->greeting(__("Hello :name", ['name' => $notifiable->name]))
            ->view("mail::html.new-chat-message", ['messages' => $this->message->chat->messages()->from($this->message->user)->unread()->get()])
            ->action(__("Answer") , route("chats.index"));
    }

    public function toArray(object $notifiable) {
        return [
            'text' => __("New Message in Chat")
        ];
    }

    /**
     * add a small delay so it won't spam the users mailbox when they is currently active
     */
//    public function withDelay(object $notifiable): array {
//        return [
//            'mail' => now()->addMinutes(5),
//            'telegram' => now()->addMinutes(5)
//        ];
//    }

//    public function shouldSend() {
//        return $this->message->read_at == null  // message is still unread
//            AND $this->message->chat->messages()->where("created_at", ">", $this->message->created_at)->count() == 1; // no messages were sent after
//    }

}
