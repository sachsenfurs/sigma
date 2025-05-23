<?php

namespace App\Notifications\Ddas;

use App\Models\Ddas\ArtshowBid;
use App\Models\Ddas\ArtshowItem;
use App\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Number;

class ArtshowItemOutbidNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected ArtshowItem $item,
        protected ArtshowBid $lastBid,
        protected ArtshowBid $newBid
    ) {}

    public static function getName(): string {
        return __("Art Show Item Outbid");
    }

    // enforced channels
    protected function getVia(): array {
        return ['database'];
    }

    protected function getSubject(): ?string {
        return __("You have been outbid: :item", ['item' => $this->item->name]);
    }

    protected function getLines(): array {
        return [
            __("Someone has placed a new bid on :item:", ['item' => $this->item->name]),
            __("Your Bid").": " . Number::currency($this->lastBid->value),
            __("Current Bid").": " . Number::currency($this->newBid->value),
        ];
    }

    protected function getAction(): ?string {
        return __("View Art Show Items");
    }

    protected function getActionUrl(): string {
        return route("artshow.index");
    }
}
