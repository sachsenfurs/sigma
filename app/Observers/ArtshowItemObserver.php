<?php

namespace App\Observers;

use App\Models\Ddas\ArtshowItem;
use Illuminate\Support\Facades\Storage;

class ArtshowItemObserver
{
    public function deleted(ArtshowItem $artshowItem): void {
        if($artshowItem->image AND Storage::fileExists($artshowItem->image))
            Storage::delete($artshowItem->image);
    }
}
