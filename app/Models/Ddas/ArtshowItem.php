<?php

namespace App\Models\Ddas;

use App\Models\Ddas\Enums\Approval;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

class ArtshowItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'auction' => "boolean",
        'sold' => "boolean",
        'paid' => "boolean",
        'approval' => Approval::class,
    ];

    protected $with = [
        'artist'
    ];

    public function scopeOwn(Builder $query) {
        $query->whereHas('artist.user', function(Builder $query) {
            $query->where('artshow_artists.user_id', auth()->user()?->id);
        });
    }

    public function scopeApproved(Builder $query) {
        $query->where("approval", "=", Approval::APPROVED->value);
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

    public function imageUrl(): Attribute {
        return Attribute::make(
            get: fn() => $this->image ? Storage::url($this->image) : null
        );
    }
}
