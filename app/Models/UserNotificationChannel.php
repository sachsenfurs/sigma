<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserNotificationChannel extends Model
{
    protected $guarded = [];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    /**
     * Get a setting value from storage.
     *
     * @param  string  $key
     * @param  string  $default
     * @return mixed
     */
    public static function get(string $key, string $default = null)
    {
        $notification = self::where('user_id', auth()->user()->id)->where('notification', $key)->first();

        if (!$notification) {
            return $default;
        }

        return $notification->channel;
    }

    /**
     * Set a setting value in storage.
     * If it isn't existing, create it.
     *
     * @param  string  $key
     * @param  null|string  $value
     * @return string|bool
     */
    public static function set(string $key, ?string $value)
    {
        if ($notification = self::where('user_id', auth()->user()->id)->where('notification', $key)->first()) {
            return $notification->update(['channel' => $value]) ? $value : false;
        }

        return self::create([
            'user_id'      => auth()->user()->id,
            'notification' => $key,
            'channel'      => $value
        ]) ? $value : false;
    }

}
