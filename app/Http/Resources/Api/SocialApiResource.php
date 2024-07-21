<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SocialApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name'          => $this->description,
            'name_en'       => $this->description_en,
            'link'          => $this->link_name,
            'link_en'       => $this->link_name_en,
            'icon'          => $this->icon,
            'qr'            => $this->image_url,
            'qr_en'         => $this->image_url_en,
        ];
    }
}
