<?php

namespace App\Models\Ddas;

use App\Enums\Approval;
use App\Models\User;
use App\Observers\ArtshowItemObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

#[ObservedBy(ArtshowItemObserver::class)]
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
        'artist',
        'artshowBids',
        'highestBid'
    ];

    public function scopeOwn(Builder $query) {
        $query->whereHas('artist.user', function(Builder $query) {
            $query->where('artshow_artists.user_id', auth()->user()?->id);
        });
    }

    public function scopeApprovedItems(Builder $query) {
        $query->where("approval", "=", Approval::APPROVED->value);
    }
    public function scopeAuctionableItems(Builder $query) {
        $query->where("auction", 1)->where("sold", 0);
    }

    public function bidPossible(): bool {
        return Gate::allows("create", [ArtshowBid::class, $this]);
    }

    public function isInAuction(): bool {
        return $this->auction AND $this->artshowBids()->count() > 0;
    }

    public function approved(): Attribute {
        return Attribute::make(
            get: fn() => $this->approval == Approval::APPROVED
        );
    }

    public function descriptionLocalized(): Attribute {
        return Attribute::make(
            get: fn() => app()->getLocale() == "en" ? $this->description_en : $this->description
        );
    }

    public function artist(): BelongsTo {
        return $this->belongsTo(ArtshowArtist::class, 'artshow_artist_id');
    }

    public function artshowBids(): HasMany {
        return $this->hasMany(ArtshowBid::class);
    }

    public function latestBids(): HasMany {
        return $this->artshowBids()->latest()->limit(5);
    }

    public function highestBid(): HasOne {
        return $this->hasOne(ArtshowBid::class)->orderBy("value", "desc")->limit(1);
    }

    public function isHighestBidder(User $user = null): bool {
        if(!$user)
            $user = auth()->user();
        return $this->highestBid?->user_id == $user->id;
    }

    public function userBidOnce(User $user = null) {
        if(!$user)
            $user = auth()->user();
        return $this->artshowBids->intersect($user->artshowBids)->count() > 0;
    }

    public function minBidValue(): int {
        return $this->highestBid ? $this->highestBid->value+1 : $this->starting_bid;
    }

    public function userOutbid(User $user = null): bool {
        if(!$user)
            $user = auth()->user();
        return $this->userBidOnce() AND !$this->isHighestBidder();
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
