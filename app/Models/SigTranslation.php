<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SigTranslation extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $timestamps = false;

    public function sigEvent() {
        return $this->belongsTo(SigEvent::class);
    }

}
