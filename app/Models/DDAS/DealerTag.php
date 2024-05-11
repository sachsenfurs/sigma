<?php

namespace App\Models\DDAS;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\App;

class DealerTag extends Model
{

    public function scopeUsed(Builder $query) {
        $query->withCount("dealer")->having("dealer_count", ">", 0);
    }

    public function dealer(): BelongsToMany {
        return $this->belongsToMany(Dealer::class);
    }
    public function nameLocalized(): Attribute {
        return Attribute::make(
            get: fn() => App::getLocale() == "en" ? $this->name_en : $this->name
        );
    }
}
