<?php

namespace App\Events\Ddas;

use App\Models\Ddas\ArtshowItem;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ArtshowItemSubmitted
{
    use Dispatchable, SerializesModels;

    /**
     * user submitted a new item for the art show
     */
    public function __construct(public ArtshowItem $item) {}

}
