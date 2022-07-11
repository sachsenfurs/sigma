<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SigLocationTranslation extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function sigLocation() {
        return $this->belongsTo(SigLocation::class);
    }

}
