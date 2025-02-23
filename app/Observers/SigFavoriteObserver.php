<?php

namespace App\Observers;

use App\Models\SigFavorite;

class SigFavoriteObserver
{
    public function deleted(SigFavorite $sigFavorite): void {
        $sigFavorite->reminders()->delete();
    }
}
