<?php

namespace App\Models;

use App\Models\Traits\HasSigEvents;
use App\Models\Traits\NameIdAsSlug;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class SigHost extends Model
{
    use HasFactory,
        HasSigEvents,
        NameIdAsSlug;

    protected $guarded = [];

    public $timestamps = false;

    protected $casts = [
        'hide' => "boolean",
    ];

    protected $appends = [
        'avatar',
        'avatar_thumb',
    ];

//    protected $with = [
//        'sigEvents',
//        'user',
//    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, "reg_id", "reg_id");
    }

    public function sigEvents(): BelongsToMany {
        return $this->belongsToMany(SigEvent::class, 'sig_host_sig_events', 'sig_host_id', 'sig_event_id')->withTimestamps();
    }

    public function timetableEntries(): HasManyThrough {
        return $this->hasManyThrough(TimetableEntry::class, SigEvent::class, "id", "sig_event_id")
                ->orderBy("start");
    }

    public function scopePublic($query) {
        return $query->where('hide', false)
            ->whereHas("sigEvents", function($query) {
                $query->whereHas("timetableEntries", function($query) {
                   $query->where("hide", false);
                });
            });
    }

    public function avatar() : Attribute {
        return Attribute::make(
            get: fn() => ($this->user?->avatar ?? "")
        )->shouldCache();
    }

    public function avatarThumb() : Attribute {
        return Attribute::make(
            get: fn() => ($this->user?->avatar_thumb ?? "")
        )->shouldCache();
    }

}
