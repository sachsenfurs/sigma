<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SigEvent extends Model
{
    use HasFactory;

    protected $casts = [
        'languages' => 'array',
    ];

    protected $guarded = [];

    public function sigHost() {
        return $this->belongsTo(SigHost::class);
    }

    public function sigLocation() {
        return $this->belongsTo(SigLocation::class);
    }

    public function sigTranslation() {
        return $this->hasOne(SigTranslation::class, "sig_event_id");
    }

    public function timetableEntries() {
        return $this->hasMany(TimetableEntry::class)->orderBy("start", "ASC");
    }
    public function getTimetableCountAttribute() {
        return $this->timeTableEntries->count();
    }

    public function getNameEnAttribute() {
        return $this->sigTranslation->name ?? $this->name;
    }
    public function setNameEnAttribute($name_en) {
        if(!$this->sigTranslation) {
            $translate = $this->sigTranslation()->create([
                'language' => "en",
                'description' => $this->description,
                'name' => $name_en,
            ]);
        } else {
            $this->sigTranslation->name = $name_en;
            $this->sigTranslation->save();
        }
    }

    public function getDescriptionEnAttribute() {
        return $this->sigTranslation->description ?? $this->description;
    }

    public function setDescriptionEnAttribute($description_en) {
        if(!$this->sigTranslation) {
            $translate = $this->sigTranslation()->create([
                'language' => "en",
                'description' => $description_en,
                'name' => $this->name,
            ]);

        } else {
            $this->sigTranslation->description = $description_en;
            $this->sigTranslation->save();
        }
    }

    public function isCompletePrivate() {
        $entries = $this->timetableEntries;
        return ($entries->count() == $entries->where("hide", 1)->count());
    }
}
