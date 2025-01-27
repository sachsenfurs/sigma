<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class ArtshowItemResource extends JsonResource
{
    public static $wrap = false;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'artist_name'       => $this->artist->name,
            'current_bid'       => $this->highestBid?->value,
            'last_bid_at'       => $this->highestBid?->created_at,
            'starting_bid'      => $this->starting_bid,
            'bid_count'         => $this->artshow_bids_count,
            'image_url'         => $this->image_url ? URL::to($this->image_url) : null,
        ];
    }
}
