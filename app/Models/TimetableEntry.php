<?php

namespace App\Models;

use App\Models\Traits\HasSigEvents;
use App\Observers\TimetableEntryObserver;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

#[ObservedBy(TimetableEntryObserver::class)]
class TimetableEntry extends Model
{
    use HasFactory, HasSigEvents;

    protected $guarded = [];

    protected $casts = [
        'hide' => "boolean",
        'cancelled' => "boolean",
        'start' => 'datetime',
        'end' => "datetime",
        'updated_at' => "datetime", // according to the docs timestamps will be casted by default but it causes issues without explicitly doing it again here!
        'created_at' => "datetime",
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
        'parentEntry',
        'favorites'
    ];

    protected $with = [
//        'favorites',
        'sigLocation',
//        'sigEvent', << i dont know why this isnt working..
        'parentEntry',
    ];

    public function favorites(): HasMany {
        return $this->hasMany(SigFavorite::class);
    }

    public function reminders(): HasMany {
        return $this->hasMany(SigReminder::class);
    }

    public function scopePublic($query) {
        return $query->where('hide', false);
    }

    /**
     * (Conbook-Export) filter events without same start and end date, which indicates announcements ("Reg Opening" for example)
     */
    public function scopeNoAnnouncements($query) {
        return $query->where(DB::raw("TIMESTAMPDIFF(SECOND, start, end)"), "!=", "0");
    }

    public function sigEvent(): BelongsTo {
        return $this->belongsTo(SigEvent::class);
    }

    public function sigLocation(): BelongsTo {
        return $this->belongsTo(SigLocation::class);
    }

    public function sigTimeslots(): HasMany {
        return $this->hasMany(SigTimeslot::class);
    }
    public function getAvailableSlotCount(): int {
        $counter = 0;
        foreach($this->sigTimeslots AS $timeslot) {
            $counter += $timeslot->max_users - $timeslot->sigAttendees->count();
        }
        return $counter;
    }

    public function replacedBy(): BelongsTo {
        return $this->belongsTo(TimetableEntry::class);
    }

    public function parentEntry(): HasOne {
        return $this->hasOne(TimetableEntry::class, "replaced_by_id");
    }

    public function getHasTimeChangedAttribute(): bool {
        return ($this->parentEntry && $this->parentEntry->start != $this->start) || $this->updated_at > $this->created_at;
    }

    public function getHasLocationChangedAttribute(): bool {
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

    public function getFavStatus(): bool {
        if (auth()->user()->favorites->where('timetable_entry_id', $this->id)->first()) {
            return true;
        }

        return false;
    }

    public function maxUserRegsExeeded(User $user=null): bool {
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
            return $this->start->diff($this->end)->format("%h:%I") . " h";
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

    public function qrCode(): string {
        return Cache::remember(
            "qrcode-entry-".$this->id,
            3600 * 4,
            function(): string {
                $qrCode = new QRCode(
                    new QROptions([
//                        'version'    => 3, // use the smallest possible version for the data
                        'outputType' => 'png',
                        'scale' => 20,
                        'quietzoneSize' => 1, // the white box around the qr code
                    ])
                );
                return $qrCode->render(route("timetable-entry.show", $this));
            }
        );
    }
}
