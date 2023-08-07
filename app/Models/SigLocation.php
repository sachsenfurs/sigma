<?php

namespace App\Models;

use App\Models\Traits\NameIdAsSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SigLocation extends Model
{
    use HasFactory, NameIdAsSlug;

    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'render_ids' => "array",
        'infodisplay' => "boolean",
    ];

    public function sigEvents() {
        return $this->hasMany(SigEvent::class);
    }

    public function translation() {
        return $this->hasMany(SigLocationTranslation::class);
    }

    public function timetableEntries() {
        return $this->hasMany(TimetableEntry::class)->orderBy("start");
    }


}
