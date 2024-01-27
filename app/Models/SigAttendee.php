<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SigAttendee extends Model
{
    use HasFactory;

    /**
     * Protected fields in this model.
     * 
     * @var array
     */
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function sigTimeslot() {
        return $this->belongsTo(SigTimeslot::class);
    }

    public function timeslotReminders() {
        return auth()?->user()->timeslotReminders()->where("timeslot_id", $this->sigTimeslot->id);
    }
}
