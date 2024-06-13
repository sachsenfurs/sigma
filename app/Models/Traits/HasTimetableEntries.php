<?php

namespace App\Models\Traits;

use App\Models\TimetableEntry;

trait HasTimetableEntries {

    public function timetableEntries() {
        return $this->hasMany(TimetableEntry::class)
            ->orderBy("start");
    }

}
