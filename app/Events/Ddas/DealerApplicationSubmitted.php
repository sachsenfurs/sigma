<?php

namespace App\Events\Ddas;

use App\Models\Ddas\Dealer;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DealerApplicationSubmitted
{
    use Dispatchable, SerializesModels;

    /**
     * user submitted a new dealer application
     */
    public function __construct(
        public Dealer $dealer
    ) {}
}
