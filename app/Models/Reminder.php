<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Reminder extends Model
{
    protected $guarded = [];

    // set defaults
    protected $attributes = [
        'offset_minutes' => 15,
    ];

    protected static function booted(): void {
        /**
         * calculate the send_at time based on the remindable model (e.g. TimetableEntry)
         */
        static::creating(function(Reminder $reminder) {
            if(empty($reminder->send_at)) {
                $reminder->send_at = self::getRelevantTimestamp($reminder->remindable)->subMinutes($reminder->offset_minutes);
            }
        });
        static::updated(fn(Reminder $reminder) => self::updateSendTime($reminder->remindable));
    }

     /**
      * Notifiable Types:
      *  User::class,
      *  UserRole::class,
      *  Post\PostChannel::class,
      *
      */
    public function notifiable(): MorphTo {
        return $this->morphTo();
    }

    /**
     * Remindable Types:
     *  TimetableEntry::class,
     *  SigTimeslot::class,
     *  SigFavorite::class,
     *  Shift::class,
     *
     */
    public function remindable(): MorphTo {
        return $this->morphTo();
    }

    public static function getRelevantTimestamp(Model $remindable) {
        return match(get_class($remindable)) {
            TimetableEntry::class       => $remindable->start,
            SigTimeslot::class          => $remindable->slot_start,
            SigFavorite::class          => $remindable->timetableEntry->start,
            Shift::class                => $remindable->start,
            default                     => Carbon::parse($remindable->send_at),
        };
    }


    /**
     * Recalculate the send_at time (e.g. when the relationship object was updated).
     *
     * called in:
     *      \App\Observers\TimetableEntryObserver::updated
     *      \App\Observers\SigTimeslotObserver::updated
     */
    public static function updateSendTime(Model $remindable): void {
        $time = self::getRelevantTimestamp($remindable);
        $remindable->reminders()->rawUpdate([
            'send_at' => DB::raw("DATE_ADD('{$time}' , INTERVAL -`offset_minutes` MINUTE)"),
        ]);
    }
}
