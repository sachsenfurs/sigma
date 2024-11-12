<?php

namespace App\Observers;

use App\Models\Ddas\ArtshowItem;
use App\Settings\ArtShowSettings;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ArtshowItemObserver
{
    public function deleted(ArtshowItem $artshowItem): void {
        if($artshowItem->image AND Storage::disk('public')->fileExists($artshowItem->image))
            Storage::disk('public')->delete($artshowItem->image);
    }

}
