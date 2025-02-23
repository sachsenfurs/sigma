<?php

namespace App\Models;

use App\Models\Traits\HasNotificationRoutes;
use App\Observers\UserRoleObserver;
use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\App;

#[ObservedBy(UserRoleObserver::class)]
class UserRole extends Model implements HasColor
{
    use HasNotificationRoutes;

    protected $guarded = [];
    public $timestamps = false;
    protected $with = [
        'permissions'
    ];

    public function users(): BelongsToMany {
        return $this->belongsToMany(
            User::class,
            'user_user_roles'
        );
    }

    public function nameLocalized(): Attribute {
        return Attribute::make(
            get: fn() => App::getLocale() == "en" ? ($this->name_en ?: $this->name) : $this->name
        );
    }

    public function permissions(): HasMany {
       return $this->hasMany(UserRolePermission::class);
    }

    public function departmentInfos(): HasMany {
        return $this->hasMany(DepartmentInfo::class);
    }

    public function chats(): HasMany {
        return $this->hasMany(Chat::class);
    }

    public function scopeChattable(Builder $query): void {
        $query->where("chat_activated", true);
    }

    public function getColor(): string|array|null {
        return Color::hex($this->background_color);
    }

    /**
     * UserRole itself is not directly "notifiable" so we dispatch this event to every single member:
     */
    public function notify(mixed $instance): void {
        $this->users->each->notify($instance);
    }

    public function reminders(): MorphMany {
        return $this->morphMany(Reminder::class, "notifiable");
    }
}
