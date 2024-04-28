<?php

namespace App\Models\DDAS;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ArtshowItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'artshow_artist_id','name', 'description',
        'description_en', 'starting_bid', 'charity_percentage',
        'approved', 'additional_info','image_file',
        'sold', 'paid'
    ];

    public function scopeOwn(Builder $query) {
        $query->whereHas('artist.user', function(Builder $query) {
            $query->where('artshow_artists.user_id', auth()->user()?->id);
        });
    }

    public function artist(): BelongsTo {
        return $this->belongsTo(ArtshowArtist::class, 'artshow_artist_id');
    }

    public function artshowBids(): HasMany {
        return $this->hasMany(ArtshowBid::class);
    }

    public function artshowPickup(): HasOne {
        return $this->hasOne(ArtshowPickup::class);
    }
}
