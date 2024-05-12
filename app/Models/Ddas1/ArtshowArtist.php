<?php

namespace App\Models\Ddas;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


/**
 * Model für die ArtshowArtist Tabelle damit diese sauber aufgebaut und mit anderen Tabelle verknüpft werden kann.
 */
class ArtshowArtist extends Model
{
    use HasFactory;

    /**
     * Fillables für das Artshow Artist Model
     */
    protected $fillable = [
        'name', 'social', 'user_id'
    ];

    /**
     * User Function um die ArtshowArtists Tabelle mit der User Tabelle zu verknüpfen
     */
    public function user(): BelongsTo|null {
        return $this->belongsTo(User::class);
    }

    /**
     * artshowItems Function um die ArtshowArtist Tabelle mit der ArtshowItems Tabelle zu verknüpfen
     */
    public function artshowItems(): HasMany {
        return $this->hasMany(ArtshowItem::class);
    }
}
