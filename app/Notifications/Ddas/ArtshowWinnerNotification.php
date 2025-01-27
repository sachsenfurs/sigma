<?php

namespace App\Notifications\Ddas;

use App\Models\Ddas\ArtshowItem;
use App\Notifications\Notification;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;
use NotificationChannels\Telegram\TelegramMessage;

class ArtshowWinnerNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected string $itemsString = "";

    public function __construct(protected Collection $artshowItems) {
        $this->itemsString = collect($this->artshowItems)
            ->map(fn($i) =>
                " - "
                . __(
                    ":item by :artist, :value EUR", [
                        'item' => $i->name,
                        'artist' => $i->artist->name,
                        'value' => $i->highestBid?->value
                    ]
                )
            )
            ->join("\r\n");
    }

    public function via(Model $notifiable): array {
        return NotificationService::channels($this, $notifiable, ['database', 'mail']);
    }

    public function toMail(object $notifiable): MailMessage {
        return (new MailMessage)
            ->subject(__("Art Show Winner Notification"))
            ->line(__("You have won the following items in the art show:"))
            ->line("")
            ->line(new HtmlString(nl2br(e($this->itemsString))))
            ->line("")
            ->line(__("Please visit Dealers' Den for pickup (Refer to the con book for opening hours!)"));
    }

    public function toTelegram($notifiable) {
        if(!$notifiable->telegram_user_id)
            return false;

        return TelegramMessage::create()
            ->to($notifiable->telegram_user_id)
            ->line(__("You have won the following items in the art show:"))
            ->line("")
            ->line($this->itemsString)
            ->line("")
            ->line(__("Please visit Dealers' Den for pickup (Refer to the con book for opening hours!)"))
        ;
    }

    public function toArray(object $notifiable): array {
        return [
            'artshowItems' => collect($this->artshowItems)->pluck("id"),
        ];
    }


    public static function view($data) {
        $artshowItems = ArtshowItem::find($data['artshowItems'] ?? 0);

        return view("notifications.type.artshow-winner-notification", [
            'artshowItems' => $artshowItems
        ]);
    }

}
