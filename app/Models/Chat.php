<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    public function messages()
    {
        return $this->belongsToMany(Message::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
