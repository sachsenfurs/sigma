<?php

namespace App\Models\DDAS;

use App\Models\SigLocation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dealer extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id','name', 'info',
        'info_en', 'gallery_link', 'icon_file',
        'approved', 'sig_location.room', 'contact_way',
        'contact', 'space'
    ];

    public function user(): BelongsTo|null {
        return $this->belongsTo(User::class);
    }

    public function sigLocation(): BelongsTo|null {
        return $this->belongsTo(SigLocation::class);
    }

    public function tags(): HasMany {
        return $this->hasMany(DealerTag::class);
    }

}
