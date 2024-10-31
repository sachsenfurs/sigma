<?php

namespace App\Models;

use App\Enums\Approval;
use App\Models\Traits\HasTimetableEntries;
use App\Observers\SigEventObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\App;

#[ObservedBy(SigEventObserver::class)]
class SigEvent extends Model
{
    use HasFactory, HasTimetableEntries;

    protected $casts = [
        'languages' => 'array',
        'approval' => Approval::class,
        'attributes' => 'array',
    ];

    protected $guarded = [];

    protected $appends = [
        'name_localized',
        'description_localized',
    ];

    protected $with = [
        'timetableEntries',
    ];

    public function scopeUnprocessed(Builder $query) {
        $query->withCount("timetableEntries")->having("timetable_entries_count", 0);
    }

    public function approved(): Attribute {
        return Attribute::make(
            get: fn() => $this->approval == Approval::APPROVED
        );
    }

    public function languages(): Attribute {
        return Attribute::make(
            get: fn(string $value) => collect(json_decode($value))->sort()->toArray()
        );
    }


    public function sigHosts(): BelongsToMany {
        return $this->belongsToMany(SigHost::class, 'sig_host_sig_events')->withTimestamps();
    }

    public function primaryHost(): Attribute {
        return Attribute::make(
            get: fn() => $this->sigHosts()->oldest()->first()
        );
    }

    public function publicHosts(): Attribute {
        return Attribute::make(
            get: fn() => $this->sigHosts->filter(fn($host) => !$host->hide)
        );
    }

    public function timetableCount(): Attribute {
        return Attribute::make(
            get: fn() => $this->timetableEntries()->count()
        );
    }

    public function favoriteCount(): Attribute {
        return Attribute::make(
            get: fn() => $this->timetableEntries->pluck("favorites")->flatten()->count()
        );
    }

    public function nameLocalized(): Attribute {
        return Attribute::make(
            get: fn() => App::getLocale() == "en" ? ($this->name_en ?? $this->name) : $this->name
        );
    }
    public function nameLocalizedOther(): Attribute {
        return Attribute::make(
            get: fn() => App::getLocale() == "de" ? $this->name_en : $this->name
        );
    }

    public function descriptionLocalized(): Attribute {
        return Attribute::make(
            get: fn() => App::getLocale() == "en" ? ($this->description_en ?? $this->description) : $this->description
        );
    }

    public function descriptionLocalizedOther(): Attribute {
        return Attribute::make(
            get: fn() => App::getLocale() == "de" ? $this->description_en : $this->description
        );
    }

    public function isDescriptionPresent(): bool {
        return $this->no_text ?: filled($this->description);
    }

    public function isDescriptionEnPresent(): bool {
        return $this->no_text ?: filled($this->description_en);
    }

    public function durationHours(): Attribute {
        return Attribute::make(
            get: fn() => number_format($this->duration / 60, 1)
        );
    }

    public function isCompletelyPrivate(): bool {
        $entries = $this->timetableEntries;
        return ($entries->count() == $entries->where("hide", 1)->count());
    }

    /**
     * Determines if the event is just for information purpose on the signage (start time == end time)
     * @return bool
     */
    public function isInfoEvent(): bool {
        return $this->timetableEntries->count() > 0 AND $this->timetableEntries->filter(fn($e) => $e->duration > 0)->count() == 0;
    }

    public function sigTags(): BelongsToMany {
        return $this->belongsToMany(SigTag::class);
    }

    public function scopePublic($query) {
        return $query->whereHas("timetableEntries", function ($query) {
            $query
                ->where("hide", false)
            ;
        });
    }

    public function forms(): BelongsToMany {
        return $this->belongsToMany(SigForm::class);
    }

    public function sigTimeslots(): HasManyThrough {
        return $this->hasManyThrough(SigTimeslot::class, TimetableEntry::class);
    }

    public function departmentInfos(): HasMany {
        return $this->hasMany(DepartmentInfo::class);
    }
}
