<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SigTimeslot extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function timetableEntry() {
        return $this->belongesTo(TimetableEntry::class);
    }

    public function sigAttendees() {
        return $this->hasMany(SigAttendee::class);
    }
}
