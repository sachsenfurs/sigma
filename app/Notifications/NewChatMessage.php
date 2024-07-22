<?php

namespace App\Notifications;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;
use App\Models\UserNotificationChannel;
use Illuminate\Support\Facades\App;

class NewChatMessage extends Notification
{
    use Queueable;

    protected $department;
    protected $sender;
    protected $chat;
    protected $message;

    /**
     * Create a new notification instance.
     * @param String $department
     * @param User $sender
     * @param Chat $chat
     * @return void
     */
    public function __construct(String $department, User $sender, Chat $chat, String $message)
    {
        App::setLocale($sender->language);

        $this->department = $department;
        $this->sender = $sender;
        $this->chat = $chat;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return UserNotificationChannel::list('chat_new_message', $notifiable->id, 'mail');
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return NotificationChannels\Telegram\TelegramMessage
     */
    public function toTelegram($notifiable)
    {
        return TelegramMessage::create()
            ->to($notifiable->telegram_user_id)
            ->line(__("Hello ") . $notifiable->name . ",")
            ->line(__("the user ") . $this->sender->nickname . __(" has posted a new message in the chat with the ")  . $this->department . __(" department!"))
            ->line(__('Message: :message', ["message" => $this->message]))
            ->button(__("View Chat") , route("chats.index") . "?chat_id={{ $this->chat()->id }}");
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('New Message in the Chat with the  ') . $this->department . __(' department!'))
            ->line(__("Hello ") . $notifiable->name . ",")
            ->line(__("the user ") . $this->sender->name . __(" has posted a new message in the chat with the ")  . $this->department . __(" department."))
            ->line(__('Message: :message', ["message" => $this->message]))
            ->action(__("View Message") , route("chats.index") . "?chat_id=" . $this->chat->id);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'type'       => 'chat_new_message',
            'department' => $this->chat->department,
            'id'         => $this->chat->id
        ];
    }
}
