<?php

namespace App\Models\DDAS;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/**
 * Model für die ArtshowBid Tabelle damit diese sauber aufgebaut und mit anderen Tabelle verknüpft werden kann.
 */
class ArtshowBid extends Model
{
    /**
     * artshowItem Function um die ArtshowBids Tabelle mit der ArtshowItems Tabelle zu verknüpfen
     */
    public function artshowItem(): BelongsTo {
        return $this->belongsTo(ArtshowItem::class);
    }

    /**
     * User Function um die ArtshowBids Tabelle mit der User Tabelle zu verknüpfen
     */
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
