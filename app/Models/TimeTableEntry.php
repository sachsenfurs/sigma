<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeTableEntry extends Model
{
    use HasFactory;

    public function scopePublic($query) {
        return $query->where('hide', false);
    }

    public function sigEvent() {
        return $this->belongsTo(SigEvent::class);
    }

    public function sigLocation() {
        return $this->belongsTo(SigLocation::class)->withDefault(function() {
            return $this->sigEvent->sigLocation;
        });
    }

    public function replacedBy() {
        return $this->belongsTo(TimeTableEntry::class);
    }

    public function parentEntry() {
        return $this->hasOne(TimeTableEntry::class, "replaced_by_id");
    }

}
