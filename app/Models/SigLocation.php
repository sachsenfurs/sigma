<?php

namespace App\Models;

use App\Models\Ddas\Dealer;
use App\Models\Traits\HasSigEvents;
use App\Models\Traits\HasTimetableEntries;
use App\Models\Traits\NameIdAsSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

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

    public function sigEvents(): HasManyThrough
    {
        return $this->hasManyThrough(
            SigEvent::class,
            TimetableEntry::class,
            'sig_location_id',
            'id',
            'id',
            'sig_event_id'
        );
    }
    public function dealers(): HasMany {
        return $this->hasMany(Dealer::class);
    }
}
