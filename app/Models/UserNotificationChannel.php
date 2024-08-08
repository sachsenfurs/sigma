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
     * Get a channel value from storage.
     *
     * @param  string  $key
     * @param  string  $default
     * @return array
     */
    public static function get(string $key, string $default = null): array
    {
        $notification = self::where('user_id', auth()->user()->id)->where('notification', $key)->first();
        if (!$notification) {
            return [$default];
        }

        return json_decode($notification->channel, true);
    }

    /**
     * Set a channel value in storage.
     * If it isn't existing, create it.
     *
     * @param  string  $key
     * @param  null|string  $value
     * @return string|bool
     */
    public static function set(string $key, ?string $value): ?string
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

    /**
     * Returns an array with all selected channels for the given notification.
     *
     * @param  string  $key
     * @param  int  $userId
     * @param  string $default
     * @return array
     */
    public static function list(string $key, int $userId, string $default=""): ?array
    {
        $notification = self::where('user_id', $userId)->where('notification', $key)->get('channel')->first();

        if (!$notification) {
            return [$default];
        }

        return json_decode($notification->channel, true);
    }
}
