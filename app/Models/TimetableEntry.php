<?php

namespace App\Models;

use App\Models\Traits\HasSigEvents;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isNull;

class TimetableEntry extends Model
{
    use HasFactory, HasSigEvents;

    protected $guarded = [];

    protected $casts = [
        'hide' => "boolean",
        'cancelled' => "boolean",
        'start' => 'datetime',
        'end' => "datetime",
    ];

    protected $appends = [
        'formatted_length',
        'hasTimeChanged',
        'hasLocationChanged',
        'is_favorite',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'replaced_by_id',
        'parentEntry'
    ];

    protected $with = [
        'favorites'
    ];

    /**
     * Define the relationship between timetable-entries and their favorites.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function favorites()
    {
        return $this->hasMany(SigFavorite::class);
    }

    /**
     * Define the relationship between timetable-entries and their reminders.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reminders()
    {
        return $this->hasMany(SigReminder::class);
    }

    public function scopePublic($query) {
        return $query->where('hide', false);
    }

    public function scopeNoAnnouncements($query) {
        return $query->where(DB::raw("TIMESTAMPDIFF(SECOND, start, end)"), "!=", "0");
    }

    public function sigEvent() {
        return $this->belongsTo(SigEvent::class);
    }

    public function sigLocation() {
        return $this->belongsTo(SigLocation::class)->withDefault(function($sigLocation, $timetableEntry) {
            return $timetableEntry->sigEvent->sigLocation;
        });
    }

    public function sigTimeslots() {
        return $this->hasMany(SigTimeslot::class);
    }
    public function getAvailableSlotCount(): int {
        $counter = 0;
        foreach($this->sigTimeslots AS $timeslot) {
            $counter += $timeslot->max_users - $timeslot->sigAttendees->count();
        }
        return $counter;
    }

    public function replacedBy() {
        return $this->belongsTo(TimetableEntry::class);
    }

    public function parentEntry() {
        return $this->hasOne(TimetableEntry::class, "replaced_by_id");
    }

    public function getHasTimeChangedAttribute() {
        return ($this->parentEntry && $this->parentEntry->start != $this->start) || $this->updated_at > $this->created_at;
    }

    public function getHasLocationChangedAttribute() {
        return $this->parentEntry && $this->parentEntry->sigLocaton != $this->sigLocation;
    }

    public function duration(): Attribute {
        return Attribute::make(
            get: fn() => $this->end->diffInMinutes($this->start)
        );
    }

    public function hasEventChanged(): bool {
        return $this->updated_at->isAfter($this->created_at);
    }

    public function getFavStatus()
    {
        if (auth()->user()->favorites->where('timetable_entry_id', $this->id)->first()) {
            return true;
        }

        return false;
    }

    public function maxUserRegsExeeded(User $user)
    {
        if ($this->sigEvent->max_regs_per_day == 0 || $this->sigEvent->max_regs_per_day == null) {
            return false;
        }

        $i = 0;

        foreach ($this->sigTimeslots as $timeslot) {
            if ($timeslot->sigAttendees->contains('user_id', $user?->id)) {
                $i++;
            }
        }

        if ($this->sigEvent->max_regs_per_day <= $i) {
            return true;
        }

        return false;
    }

    public function formattedLength(): Attribute {
        return Attribute::make(function() {
            $mins =  $this->start->diffInMinutes($this->end);
            if($mins == 0)
                return "";
            if($mins < 60)
                return $mins." min";
            return round($this->start->floatDiffInHours($this->end), 1). " h";
        });
    }

    public function isFavorite(): Attribute {
        return Attribute::make(function() {
            if(Auth::check()) {
                return $this->favorites->where("user_id", auth()->user()->id)->count() > 0;
            }
            return false;
        });
    }
}
