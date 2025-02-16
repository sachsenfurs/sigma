<?php

namespace App\Models\Traits;

use App\Models\Chat;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasChats
{
    public function chats(): MorphMany {
        return $this->morphMany(Chat::class, "subjectable");
    }
}
