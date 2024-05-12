<?php

namespace App\Models\Ddas;

use App\Models\SigLocation;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\App;

class Dealer extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = [
        'info_localized'
    ];

    protected $hidden = [
        'contact_way'
    ];

    public function scopeApproved(\Illuminate\Database\Eloquent\Builder $query) {
        $query->where("approved", "=", 1);

    }
    public function user(): BelongsTo|null {
        return $this->belongsTo(User::class);
    }

    public function sigLocation(): BelongsTo|null {
        return $this->belongsTo(SigLocation::class);
    }

    public function tags(): BelongsToMany {
        return $this->belongsToMany(DealerTag::class);
    }

    public function infoLocalized(): Attribute {
        return Attribute::make(
            get: fn() => App::getLocale() == "en" ? $this->info_en : $this->info
        );
    }

}
