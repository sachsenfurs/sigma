<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SigTranslation extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $timestamps = false;

    protected $primaryKey = "sig_event_id";

    public function sigEvent() {
        return $this->belongsTo(SigEvent::class);
    }

}
