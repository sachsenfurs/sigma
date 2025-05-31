<?php

namespace App\Models;

use App\Models\Traits\HasReminders;
use App\Observers\SigFavoriteObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

#[ObservedBy(SigFavoriteObserver::class)]
class SigFavorite extends Model
{
    use HasReminders;

    protected $guarded = [];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function timetableEntry(): BelongsTo {
        return $this->belongsTo(TimetableEntry::class);
    }

    public function scopeUpcoming(Builder $query) {
        $query->whereHas("timetableEntry", function (Builder $query) {
            return $query->where("start", ">", now())
                ->orWhere(function (Builder $query) {
                   return $query->where("start", "<", now())->where("end", ">", now());
                });
        });
    }

    public static function getMaxLikes() {
        return Cache::remember(
            "timetable-max-likes",
            500,
            fn() => TimetableEntry::withCount("favorites")->orderBy("favorites_count", "desc")->first()?->favorites_count ?? 0
        );
    }
}
