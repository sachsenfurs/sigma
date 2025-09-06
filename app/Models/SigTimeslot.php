<?php

namespace App\Models;

use App\Models\Traits\HasReminders;
use App\Observers\SigTimeslotObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ObservedBy(SigTimeslotObserver::class)]
class SigTimeslot extends Model
{
    use HasReminders;

    protected $guarded = [];

    protected $casts = [
        'reg_start' => 'datetime',
        'reg_end' => 'datetime',
        'slot_start' => 'datetime',
        'slot_end' => 'datetime',
    ];

    public function scopeUpcoming(Builder $query): void {
        $query->where("slot_end", ">", now()->addMinutes(15));
    }

    public function timetableEntry(): BelongsTo {
        return $this->belongsTo(TimetableEntry::class);
    }

    public function sigAttendees(): HasMany {
        return $this->hasMany(SigAttendee::class)->orderBy("created_at");
    }

    public function getOwner(): ?SigAttendee {
        return $this->sigAttendees->sortBy("created_at")->first();
    }

    public function isFull(): bool {
        return $this->sigAttendees->count() >= $this->max_users;
    }

    public function isWithinRegTime(): bool {
        return $this->reg_start->isPast() AND $this->reg_end->isFuture();
    }

    public function isUserRegistered(?User $user = null): bool {
        if(!$user)
            $user = auth()->user();
        if($user)
            return $this->sigAttendees->contains("user_id", $user->id);
        return false;
    }
}
