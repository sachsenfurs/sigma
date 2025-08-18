<?php

namespace App\Notifications\Ddas;

use App\Models\Ddas\ArtshowItem;
use App\Notifications\Notification;
use App\Services\PageHookService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Number;
use NotificationChannels\Telegram\TelegramMessage;

class ArtshowWinnerNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected string $itemsString = "";
    public static bool $userSetting = false;

    public function __construct(protected Collection $artshowItems) {
        $this->itemsString = collect($this->artshowItems)
            ->map(fn($i) =>
                " - "
                . __(
                    ":item by :artist, :value", [
                        'item' => $i->name,
                        'artist' => $i->artist->name,
                        'value' => Number::currency($i->highestBid?->value ?? 0)
                    ]
                )
            )
            ->join("\r\n");
    }

    protected function getVia(): array {
        return ['database', 'mail', 'telegram'];
    }

    public function toMail(object $notifiable): Renderable {
        return (new MailMessage)
            ->subject(__("Art Show Winner Notification"))
            ->line(__("You have won the following items in the art show:"))
            ->line("")
            ->line(new HtmlString(nl2br(e($this->itemsString))))
            ->line("")
            ->line(PageHookService::resolve("artshow.notification.winner.info", __("Please visit Dealers' Den for pickup (Refer to the con book for opening hours!)")));
    }

    public function toTelegram($notifiable): TelegramMessage {
        return TelegramMessage::create()
            ->line(__("You have won the following items in the art show:"))
            ->line("")
            ->line($this->cleanMarkdown($this->itemsString))
            ->line("")
            ->line(PageHookService::resolve("artshow.notification.winner.info", __("Please visit Dealers' Den for pickup (Refer to the con book for opening hours!)")));
    }

    public function toArray(object $notifiable): array {
        return [
            'artshowItems' => collect($this->artshowItems)->pluck("id"),
        ];
    }


    public static function view($data) {
        $data['artshowItems'] = ArtshowItem::find($data['artshowItems'] ?? 0);

        return view("notifications.type.artshow-winner-notification", $data);
    }

}
