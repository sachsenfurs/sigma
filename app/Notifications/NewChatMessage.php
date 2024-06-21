<?php

namespace App\Notifications;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;
use App\Models\UserNotificationChannel;

class NewChatMessage extends Notification
{
    use Queueable;

    protected $department;
    protected $sender;
    protected $chat;

    /**
     * Create a new notification instance.
     *
     * @param TimetableEntry $entry
     * @param SigFavorite $fav
     * @return void
     */
    public function __construct(String $department, User $sender, Chat $chat)
    {
        App::setLocale($sender->language);

        $this->department = $department;
        $this->sender = $sender;
        $this->chat = $chat;
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
            ->line(__("Hi ") . $notifiable->name . ",")
            ->line(__("the user ") . $this->sender->nickname . __(" has posted a new message in the chat with the ")  . $this->department . __(" department!"))
            ->button(__("View Message") , route("chats.index") . "?chat_id={{ $this->chat()->id }}");
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('New Message in the Chat with the  ') . $this->department . __(' department!'))
            ->line(__("Hi ") . $notifiable->name . ",")
            ->line(__("the user ") . $this->sender->nickname . __(" has posted a new message in the chat with the ")  . $this->department . __(" department!"))
            ->action(__("View Message") , route("chats.index") . "?chat_id={{ $this->chat()->id }}");
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
            'type' => 'chat_new_message',
            'data' => $this->chat->id
        ];
    }
}
