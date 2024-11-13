<?php

namespace App\Models\Ddas;

use App\Enums\Approval;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ArtshowArtist extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'social', 'user_id'
    ];

    public function user(): BelongsTo|null {
        return $this->belongsTo(User::class);
    }

    public function artshowItems(): HasMany {
        return $this->hasMany(ArtshowItem::class);
    }

    public function scopeHavingApprovedItems(Builder $query): void {
        $query->whereHas("artshowItems", function(Builder $query) {
            $query->where("approval", Approval::APPROVED);
        });
    }
}
