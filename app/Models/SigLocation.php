<?php

namespace App\Models;

use App\Models\Traits\HasSigEvents;
use App\Models\Traits\HasTimetableEntries;
use App\Models\Traits\NameIdAsSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SigLocation extends Model
{
    use HasFactory, HasSigEvents, HasTimetableEntries, NameIdAsSlug;

    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'render_ids' => "array",
        'infodisplay' => "boolean",
        'show_default' => "boolean",
    ];


    public function translation() {
        return $this->hasMany(SigLocationTranslation::class);
    }



}
