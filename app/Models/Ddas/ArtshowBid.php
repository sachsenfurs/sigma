<?php

namespace App\Models\Ddas;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArtshowBid extends Model
{

    protected $guarded = [];

    public function artshowItem(): BelongsTo {
        return $this->belongsTo(ArtshowItem::class);
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
