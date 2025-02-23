<?php

namespace App\Models;

use App\Observers\SigAttendeeObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ObservedBy(SigAttendeeObserver::class)]
class SigAttendee extends Model
{
    protected $guarded = [];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function sigTimeslot(): BelongsTo {
        return $this->belongsTo(SigTimeslot::class);
    }

    public function isOwner(): Attribute {
        return Attribute::make(
            get: fn() => $this->sigTimeslot?->getOwner()?->user_id == auth()->id()
        )->shouldCache();
    }

    public function reminders(): HasMany {
        return $this->hasMany(Reminder::class, "remindable_id", "id")->where("remindable_type", SigTimeslot::make()->getMorphClass());
    }
}
