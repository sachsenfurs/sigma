<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DealerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'gallery_link' => $this->gallery_link,
            'gallery_link_name' => $this->gallery_link_name,
            'icon_file' => $this->icon_file_url,
            'info' => $this->info_localized,
            'location' => $this->sigLocation?->name,
            'tags' => $this->tags->select('id','name_localized'),
        ];
    }
}
