<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SigFavorite extends Model
{
    use HasFactory;

    /**
     * Protected fields in this model.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Define the relationship between favorites and their users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define the relationship between favorites and their timetable-entry.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function timetableEntry()
    {
        return $this->belongsTo(TimetableEntry::class);
    }

    public function scopeUpcoming($query) {
        return $query->join("timetable_entries", "timetable_entries.id", "sig_favorites.timetable_entry_id")->where("start", ">", now());
    }

    public function reminders() {
        return auth()?->user()->reminders()->where("timetable_entry_id", $this->timetableEntry->id);
    }
}
