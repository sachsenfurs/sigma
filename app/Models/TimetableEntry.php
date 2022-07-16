<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimetableEntry extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'hide' => "boolean",
        'cancelled' => "boolean",
        'start' => 'datetime',
        'end' => "datetime",
    ];

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
        return $this->belongsTo(TimetableEntry::class);
    }

    public function parentEntry() {
        return $this->hasOne(TimetableEntry::class, "replaced_by_id");
    }

}
