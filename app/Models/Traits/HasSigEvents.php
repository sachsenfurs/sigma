<?php

namespace App\Models\Traits;

use App\Models\SigEvent;

trait HasSigEvents {

    public function sigEvents() {
        return $this->hasMany(SigEvent::class);
    }

    public function getPublicSigEventCount() {
        return $this->sigEvents()
                    ->with("timeTableEntries")
                    ->get()
                    ->filter(function($sigEvent) {
                        return !$sigEvent->isCompletelyPrivate();
                    })->count();
    }

}
