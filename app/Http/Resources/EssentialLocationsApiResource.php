<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EssentialLocationsApiResource extends JsonResource
{
    public static $wrap = false;
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->description,
            'name_en' => $this->description_en,
            'location_ids' => $this->render_ids,
            'description' => $this->essential_description,
            'description_en' => $this->essential_description_en,
        ];
    }
}
