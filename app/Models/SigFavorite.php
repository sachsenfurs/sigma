<?php

namespace App\Models;

use App\Observers\SigFavoriteObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

#[ObservedBy(SigFavoriteObserver::class)]
class SigFavorite extends Model
{

    protected $guarded = [];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function timetableEntry(): BelongsTo {
        return $this->belongsTo(TimetableEntry::class);
    }

    public function scopeUpcoming($query) {
        return $query->join("timetable_entries", "timetable_entries.id", "sig_favorites.timetable_entry_id")->where("start", ">", now());
    }

    public function reminders() {
        return auth()?->user()->reminders()->where("timetable_entry_id", $this->timetableEntry->id);
    }

    public static function getMaxLikes() {
        return Cache::remember(
            "timetable-max-likes",
            500,
            fn() => TimetableEntry::withCount("favorites")->orderBy("favorites_count", "desc")->first()?->favorites_count ?? 0
        );
    }
}
