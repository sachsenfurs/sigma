<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SigLocation extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $casts = [
        'render_ids' => "array",
    ];

    public function sigEvents() {
        return $this->hasMany(SigEvent::class);
    }

    public function translation() {
        return $this->hasMany(SigLocationTranslation::class);
    }
}
