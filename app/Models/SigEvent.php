<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SigEvent extends Model
{
    use HasFactory;

    protected $casts = [
        'languages' => 'array',
    ];

    public function sigHost() {
        return $this->belongsTo(SigHost::class);
    }

    public function sigLocation() {
        return $this->belongsTo(SigLocation::class);
    }

    public function sigTranslation() {
        return $this->belongsTo(SigTranslation::class);
    }

    public function timeTableEntries() {
        return $this->hasMany(TimeTableEntry::class);
    }
    public function getTimeTableCountAttribute() {
        return $this->timeTableEntries->count();
    }

}
