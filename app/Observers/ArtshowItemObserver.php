<?php

namespace App\Observers;

use App\Events\Ddas\ArtshowItemSubmitted;
use App\Facades\NotificationService;
use App\Models\Ddas\ArtshowItem;
use App\Notifications\Ddas\ArtshowItemSubmittedNotification;
use Illuminate\Support\Facades\Storage;

class ArtshowItemObserver
{
    public function deleted(ArtshowItem $artshowItem): void {
        if($artshowItem->image AND Storage::disk('public')->fileExists($artshowItem->image))
            Storage::disk('public')->delete($artshowItem->image);
    }

    public function artshowItemSubmitted(ArtshowItemSubmitted $event): void {
        NotificationService::dispatchRoutedNotification(new ArtshowItemSubmittedNotification($event->item));
    }

    public function subscribe(): array {
        return [
            ArtshowItemSubmitted::class => 'artshowItemSubmitted',
        ];
    }
}
