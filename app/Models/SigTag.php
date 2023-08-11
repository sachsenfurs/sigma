<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SigTag extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function sigEvent() {
        return $this->belongsToMany(SigEvent::class);
    }

}
