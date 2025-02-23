<?php

namespace App\Observers;

use App\Events\Ddas\ArtshowItemSubmitted;
use App\Facades\NotificationService;
use App\Models\Ddas\ArtshowItem;
use App\Notifications\Ddas\ProcessedItemNotification;
use App\Notifications\Ddas\SubmittedItemNotification;
use Illuminate\Support\Facades\Storage;

class ArtshowItemObserver
{
    public function deleted(ArtshowItem $artshowItem): void {
        if($artshowItem->image AND Storage::disk('public')->fileExists($artshowItem->image))
            Storage::disk('public')->delete($artshowItem->image);

        foreach($artshowItem->chats AS $chat) {
            $chat->subjectable()->dissociate();
            $chat->save;
        }
    }

    public function updated(ArtshowItem $item) {
        if($item->isDirty("approval")) {
            $item->artist->user->notify(new ProcessedItemNotification($item));
        }
    }

    public function artshowItemSubmitted(ArtshowItemSubmitted $event): void {
        NotificationService::dispatchRoutedNotification(new SubmittedItemNotification($event->item));
    }

    public function subscribe(): array {
        return [
            ArtshowItemSubmitted::class => 'artshowItemSubmitted',
        ];
    }
}
