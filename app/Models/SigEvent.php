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
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\App;

#[ObservedBy(SigEventObserver::class)]
class SigEvent extends Model
{
    use HasFactory, HasTimetableEntries;

    protected $casts = [
        'languages' => 'array',
        'approval' => Approval::class
    ];

    protected $guarded = [];

    protected $appends = [
        'name_localized',
        'description_localized'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $with = [
        'timetableEntries'
    ];

    public function scopeUnprocessed(Builder $query) {
        $query->withCount("timetableEntries")->having("timetable_entries_count", 0);
    }

    public function approved(): Attribute {
        return Attribute::make(
            get: fn() => $this->approval == Approval::APPROVED
        );
    }

    public function sigHost(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
        return $this->belongsTo(SigHost::class);
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

    public function durationHours(): Attribute {
        return Attribute::make(
            get: fn() => number_format($this->duration / 60, 1)
        );
    }

    public function isCompletelyPrivate(): bool {
        $entries = $this->timetableEntries;
        return ($entries->count() == $entries->where("hide", 1)->count());
    }

    public function sigTags(): \Illuminate\Database\Eloquent\Relations\BelongsToMany {
        return $this->belongsToMany(SigTag::class);
    }

    public function scopePublic($query) {
        return $query->whereHas("timetableEntries", function ($query) {
            $query
                ->where("hide", false)
                ->whereRaw("start != end")
            ;
        });
    }

    public function forms(): HasMany {
        return $this->hasMany(SigForm::class);
    }
}
