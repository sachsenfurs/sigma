<?php

namespace App\Models\DDAS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ArtshowItem extends Model
{
    use HasFactory;

    public function artist(): BelongsTo {
        return $this->belongsTo(ArtshowArtist::class);
    }

    public function artshowBids(): HasMany {
        return $this->hasMany(ArtshowBid::class);
    }

    public function artshowPickup(): HasOne {
        return $this->hasOne(ArtshowPickup::class);
    }
}
