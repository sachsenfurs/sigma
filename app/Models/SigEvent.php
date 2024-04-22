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

    protected $with = [
        'timetableEntries'
    ];

    public function sigHost(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
        return $this->belongsTo(SigHost::class);
    }

    public function getTimetableCountAttribute() {
        return $this->timetableEntries()->count();
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

    public function sigTags(): \Illuminate\Database\Eloquent\Relations\BelongsToMany {
        return $this->belongsToMany(SigTag::class);
    }

    public function scopePublic($query) {
        return $query->whereHas("timetableEntries", function($query) {
            $query->where("hide", false);
        });
    }
}
