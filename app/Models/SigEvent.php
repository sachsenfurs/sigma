<?php

namespace App\Models;

use App\Models\Traits\HasTimetableEntries;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class SigEvent extends Model
{
    use HasFactory, HasTimetableEntries;

    protected $casts = [
        'languages' => 'array',
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

    public function sigHost() {
        return $this->belongsTo(SigHost::class);
    }

    public function sigLocation() {
        return $this->belongsTo(SigLocation::class);
    }

    public function sigTranslation() {
        return $this->hasOne(SigTranslation::class, "sig_event_id");
    }

    public function getTimetableCountAttribute() {
        return $this->timetableEntries->count();
    }

    public function getNameEnAttribute() {
        return $this->sigTranslation->name ?? null;
    }

    public function getDescriptionEnAttribute() {
        return $this->sigTranslation->description ?? null;
    }

    public function getNameLocalizedAttribute() {
        return App::getLocale() == "en" ? ($this->name_en ?? $this->name) : $this->name;
    }

    public function getDescriptionLocalizedAttribute() {
        return App::getLocale() == "en" ? ($this->description_en ?? $this->description) : $this->description;
    }

    public function isCompletelyPrivate() {
        $entries = $this->timetableEntries;
        return ($entries->count() == $entries->where("hide", 1)->count());
    }

    public function sigTags() {
        return $this->belongsToMany(SigTag::class);
    }

    public function scopePublic($query) {
        return $query->whereHas("timetableEntries", function($query) {
            $query->where("hide", false);
        });
    }
}
