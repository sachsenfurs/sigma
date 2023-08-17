<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SigTimeslot extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'reg_start' => 'datetime',
        'reg_end' => 'datetime',
    ];

    public function timetableEntry() {
        return $this->belongsTo(TimetableEntry::class);
    }

    public function sigAttendees() {
        return $this->hasMany(SigAttendee::class);
    }
}
