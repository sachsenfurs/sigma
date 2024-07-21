<?php

namespace App\Http\Resources\Api;

use App\Models\SigTag;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SigTagApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /**
         * @var $this SigTag
         */
        return [
            'name' => $this->name,
            'description' => $this->description,
            'description_en' => $this->description_en,
        ];
    }
}
