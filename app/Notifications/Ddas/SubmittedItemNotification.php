<?php

namespace App\Notifications\Ddas;

use App\Filament\Resources\Ddas\ArtshowItemResource;
use App\Models\Ddas\ArtshowItem;
use App\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SubmittedItemNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public static bool $userSetting = false;
    public function __construct(protected ArtshowItem $item) {}


    public static function getName(): string {
        return __("Art Show Item Submitted");
    }

    protected function getVia(): array {
        return [];
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

    protected function getAction(): ?string {
        return __("Review");
    }

    protected function getActionUrl(): string {
        return ArtshowItemResource::getUrl('edit', ['record' => $this->item]);
    }

}
