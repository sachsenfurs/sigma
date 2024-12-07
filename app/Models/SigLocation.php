<?php

namespace App\Models;

use App\Models\Ddas\Dealer;
use App\Models\Traits\HasSigEvents;
use App\Models\Traits\HasTimetableEntries;
use App\Models\Traits\NameIdAsSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\App;
use phpDocumentor\Reflection\Types\Boolean;

class SigLocation extends Model
{
    use HasFactory, HasSigEvents, HasTimetableEntries, NameIdAsSlug;

    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'render_ids' => "array",
        'infodisplay' => "boolean",
        'show_default' => "boolean",
        'essential' => "boolean"
    ];

    protected $appends = [
        'name_localized',
        'description_localized',
    ];

    public function sigEvents(): HasManyThrough {
        return $this->hasManyThrough(
            SigEvent::class,
            TimetableEntry::class,
            'sig_location_id',
            'id',
            'id',
            'sig_event_id'
        )
        ->distinct();
    }

    public function dealers(): HasMany {
        return $this->hasMany(Dealer::class);
    }

    /**
     * Scopes to SigLocation which are assigned to at least one event
     * @return void
     */
    public function scopeUsed(Builder $query) {
        $query->withCount("timetableEntries")->having("timetable_entries_count",">", 0);
    }

    public function description(): Attribute {
        return Attribute::make(
            get: fn($description="") => $description != "" ? $description : $this->name
        );
    }

    public function descriptionLocalized(): Attribute {
        return Attribute::make(
            get: fn() => App::getLocale() == "en" ? $this->description_en : $this->description
        );
    }

    public function essentialDescriptionLocalized(): Attribute {
        return Attribute::make(
            get: fn() => App::getLocale() == "en" ? $this->essential_description_en : $this->essential_description
        );
    }

    public function descriptionEn(): Attribute {
        return Attribute::make(
            get: fn($description_en="") => $description_en != "" ? $description_en : ($this->name_en ?? $this->name)
        );
    }

    public function nameLocalized(): Attribute {
        return Attribute::make(
            get: fn() => App::getLocale() == "en" ? $this->name_en : $this->name
        );
    }
    public function nameEn(): Attribute {
        return Attribute::make(
            get: fn($name_en="") => $name_en ?? $this->name
        );
    }

}
