<?php

namespace App\Events\Sig;

use App\Models\SigEvent;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SigApplicationSubmitted
{
    use Dispatchable, SerializesModels;

    public function __construct(public SigEvent $sigEvent) {}

}
