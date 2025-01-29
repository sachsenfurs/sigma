<?php

namespace App\Models\Traits;

use App\Models\SigEvent;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

trait HasSigEvents {

    public function sigEvents() : HasMany {
        return $this->hasMany(SigEvent::class)
            ->orderByRaw("
             (
                SELECT START FROM timetable_entries
                WHERE timetable_entries.sig_event_id = sig_events.id
                ORDER BY start
                LIMIT 1
             )
        ");
    }

    public function publicSigEventCount(): Attribute {
        return Attribute::make(
            get: fn() => $this->sigEvents
                ->filter(function($sigEvent) {
                    return !$sigEvent->isInfoEvent();
                })
                ->count()
        )->shouldCache();
    }

}
