<?php

namespace App\Models;

use App\Enums\Approval;
use App\Models\Traits\HasChats;
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
    use HasFactory, HasTimetableEntries, HasChats;

    protected $casts = [
        'languages' => 'array',
        'approval' => Approval::class,
        'attributes' => 'array',
        'private_group_ids' => 'array',
    ];

    protected $guarded = [];

    protected $appends = [
        'name_localized',
        'description_localized',
    ];

    /**
     * addGlobalScope('private')
     *  -> global scope is defined in AppServiceProvider
     *     defining it within the models booted() method won't work in some cases (when working with eager loading). probably a laravel bug?
     */

    public static function applyPrivateScope(): \Closure {
        return function($query) {
            $query
                ->whereNull('private_group_ids')
                ->orWhere('private_group_ids', '[]')
                ->orWhereJsonOverlaps('private_group_ids', auth()->user()?->roles?->pluck("id") ?? [])
            ;
        };
    }

    public function scopeUnprocessed(Builder $query) {
        $query->withCount("timetableEntries")->having("timetable_entries_count", 0);
    }

    public function scopePublic($query) {
        return $query->whereHas("timetableEntries", function ($query) {
            $query
                ->where("hide", false)
            ;
        });
    }
    public function isPrivate(): Attribute {
        return Attribute::make(
            get: fn() => count($this->private_group_ids ?? []) > 0
        );
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

    public function favorites(): HasManyThrough {
        return $this->hasManyThrough(SigFavorite::class, TimetableEntry::class);
    }

    public function sigTags(): BelongsToMany {
        return $this->belongsToMany(SigTag::class);
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

    public function primaryHost(): Attribute {
        return Attribute::make(
            get: fn(): ?SigHost => $this->sigHosts->first()
        )->shouldCache();
    }

    public function publicHosts(): Attribute {
        return Attribute::make(
            get: fn() => $this->sigHosts->filter(fn($host) => !$host->hide)
        )->shouldCache();
    }

    public function timetableCount(): Attribute {
        return Attribute::make(
            get: fn() => $this->timetableEntries->count()
        )->shouldCache();
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

    /**
     * Determines if the event is just for information purpose on the signage (start time == end time)
     * @return bool
     */
    public function isInfoEvent(): bool {
        return $this->timetableEntries->count() > 0 AND $this->timetableEntries->filter(fn($e) => $e->duration > 0)->count() == 0;
    }

}
