<?php

namespace App\Http\Resources;

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
            'name'          => $this->name,
            'name_en'       => $this->name_en,
            'link'          => $this->link,
            'link_en'       => $this->link_en,
            'icon'          => $this->icon,
            'qr'            => $this->qr,
            'qr_en'         => $this->qr_en,
        ];
    }
}
