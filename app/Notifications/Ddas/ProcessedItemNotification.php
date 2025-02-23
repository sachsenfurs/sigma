<?php

namespace App\Notifications\Ddas;

use App\Models\Ddas\ArtshowItem;
use App\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessedItemNotification extends Notification implements ShouldQueue
{
    use Queueable;
    public function __construct(protected ArtshowItem $item) {}


    public static function getName(): string {
        return __("Art Show Item Processed");
    }

    protected function getVia(): array {
        return ['mail', 'telegram', 'database'];
    }

    protected function getSubject(): ?string {
        return __("Art Show") . " - " .$this->item->name . ": " . $this->item->approval->name();
    }

    protected function getLines(): array {
        return [
            __('The status of your submitted art show item ":item" has been changed: :approval', ['item' => $this->item->name, 'approval' => $this->item->approval->name()]),
            "",
            $this->item->description,
            "",
            $this->item->description_localized,
        ];
    }

    protected function getAction(): ?string {
        return __("Review");
    }

    protected function getActionUrl(): string {
        return route("artshow.create");
    }

}
