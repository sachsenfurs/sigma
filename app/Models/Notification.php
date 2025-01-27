<?php

namespace App\Models;

use Illuminate\Notifications\DatabaseNotification;

class Notification extends DatabaseNotification
{
    public function view() {
        if(is_subclass_of($this->type, \App\Notifications\Notification::class)) {
            return $this->type::view([...$this->data, 'notification' => $this]);
        }

        return \App\Notifications\Notification::view([...$this->data, 'notification' => $this]);
    }
}
