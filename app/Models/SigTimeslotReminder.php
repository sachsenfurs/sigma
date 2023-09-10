<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SigTimeslotReminder extends Model
{
    use HasFactory;

    /**
     * Protected fields in this model.
     * 
     * @var array
     */
    protected $guarded = [];

    /**
     * Define the relationship between reminders and their users.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define the relationship between reminders and their timeslot-entry.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function timeslot()
    {
        return $this->belongsTo(SigTimeslot::class);
    }
}
