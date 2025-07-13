<?php

namespace App\Notifications\Ddas;

use App\Filament\Resources\Ddas\ArtshowItemResource;
use App\Models\Ddas\ArtshowItem;
use App\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;
use NotificationChannels\Telegram\TelegramBase;
use NotificationChannels\Telegram\TelegramFile;
use NotificationChannels\Telegram\TelegramMessage;

class SubmittedItemNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected ArtshowItem $item) {}


    public static function getName(): string {
        return __("Art Show Item Submitted");
    }

    protected function getSubject(): ?string {
        return __("New Art Show Item Submitted: :name", ['name' => $this->item->name]);
    }

    protected function getLines(): array {
        return [
            __(":artist just submitted a new item for the art show: :name", ['artist' => $this->item->artist->name, 'name' => $this->item->name]),
            "",
            $this->item->description,
            "",
            $this->item->additional_info,
        ];
    }

    public function toTelegram(object $notifiable): TelegramBase {
        if($this->item->image) {
            return TelegramFile::create()
                ->content("**" . $this->getSubject() . "**\n\n".collect($this->getLines())->join("\n")."\n\n".$this->getActionUrl())
                ->file(Storage::disk("public")->path($this->item->image), 'photo');
        } else {
            return TelegramMessage::create()
                ->line("**" . $this->getSubject() . "**")->line("")
                ->line(collect($this->getLines())->join("\n"))
                ->button($this->getAction(), $this->getActionUrl());
        }
    }

    protected function getAction(): ?string {
        return __("Review");
    }

    protected function getActionUrl(): string {
        return ArtshowItemResource::getUrl('edit', ['record' => $this->item]);
    }

}
