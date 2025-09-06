<?php

namespace App\Models;

use App\Enums\Approval;
use App\Models\Traits\HasReminders;
use App\Models\Traits\NameIdAsSlug;
use App\Observers\TimetableEntryObserver;
use App\Settings\AppSettings;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

#[ObservedBy(TimetableEntryObserver::class)]
class TimetableEntry extends Model
{
    use HasFactory,
        HasReminders,
        NameIdAsSlug;

    protected $guarded = [];

    protected $casts = [
        'hide' => "boolean",
        'cancelled' => "boolean",
        'start' => 'datetime',
        'end' => "datetime",
        'new' => "boolean",
        'updated_at' => "datetime", // according to the docs timestamps will be casted by default but it causes issues without explicitly doing it again here!
        'created_at' => "datetime",
        'approval' => Approval::class,
    ];

    protected $appends = [
        'formatted_length',
        'has_time_changed',
        'has_location_changed',
        'is_favorite',
        'slug',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'replaced_by_id',
        'parentEntry',
        'favorites'
    ];

    protected $with = [
//        'sigLocation',
//        'parentEntry', // for future use, not used atm
    ];

    /**
     * addGlobalScope('private')
     *  -> global scope is defined in AppServiceProvider
     *     defining it within the models booted() method won't work in some cases (when working with eager loading). probably a laravel bug?
     */


    public function favorites(): HasMany {
        return $this->hasMany(SigFavorite::class);
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
        return $this->hasMany(SigTimeslot::class)->orderBy("slot_start");
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

    public function hasTimeChanged(): Attribute {
        return Attribute::make(
            get: fn() => /**($this->parentEntry && $this->parentEntry->start != $this->start) || **/ $this->updated_at > $this->created_at
        )->shouldCache();
    }

    public function hasLocationChanged(): Attribute {
        return Attribute::make(
            get: fn() => /**$this->parentEntry && $this->parentEntry->sigLocaton != $this->sigLocation**/ false
        )->shouldCache();
    }

    public function duration(): Attribute {
        return Attribute::make(
            get: fn() => $this->start->diffInMinutes($this->end)
        );
    }

    public function hasEventChanged(): bool {
        return $this->updated_at->isAfter($this->created_at);
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
                return $this->favorites->where("user_id", auth()->id())->count() > 0;
            }
            return false;
        })->shouldCache();
    }

    public function qrCode(): string {
        return Cache::remember(
            "qrcode-entry-".$this->id.(app(AppSettings::class)->short_domain?:""),
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
                $route = app(AppSettings::class)->short_domain
                    ? "https://" . app(AppSettings::class)->short_domain . $this->id
                    : $this->routeUrl("timetable-entry.show");
                return $qrCode->render($route);
            }
        );
    }

    public function eventColor(): Attribute {
        return Attribute::make(
            // returns array [ <backgroundColor>, <borderColor> ]
            get: function() {
                if ($this->hide)
                    return ['#948E8A'];
                if ($this->cancelled)
                    return ['#EB8060'];
                if ($this->is_favorite)
                    return ['#2C4F31', 'rgb(64, 115, 72)'];
                if ($this->sigEvent->is_private)
                    return ['#9f3ab6', '#6e2651'];
                return [ '#2C3D4F' ];
            }
        )->shouldCache();
    }

    public function resolveRouteBinding($value, $field = null) {
        $parts = explode($this->slugChecksum(), $value);
        $id = $parts[0] ?? 0;

        
        $instances = self::where("id", $id)->orWhereHas("sigEvent", function(Builder $query) use ($value) {
            $query->where("name", $value)->orWhere("name_en", $value);
        });

        return $instances->first();
    }

    public function slug() : Attribute {
        return Attribute::make(function() {
            return $this->id.$this->slugChecksum()."-".urlencode(Str::slug($this->sigEvent->name_localized));
        });
    }

    public function detailedNameLocalized(): Attribute {
        return Attribute::make(
            get: fn() => $this->sigEvent->name_localized . " | " . $this->start->translatedFormat("l | H:i"),
        );
    }
}
