<?php

namespace App\Models\DDAS;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ArtshowArtist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user.reg_id','name', 'social'
    ];

    public function user(): BelongsTo|null {
        return $this->belongsTo(User::class);
    }

    public function artshowItems(): HasMany {
        return $this->hasMany(ArtshowItem::class);
    }



}
