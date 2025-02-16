<?php

namespace App\Events\Sig;

use App\Models\SigEvent;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SigApplicationSubmitted
{
    use Dispatchable, SerializesModels;

    /**
     * user submitted a new registration for a SIG
     */
    public function __construct(public SigEvent $sigEvent) {}

}
