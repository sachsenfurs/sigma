<?php

namespace App\Http\Resources;

use App\Models\SigLocation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SigLocationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /**
         * @var $this SigLocation
         */

        self::withoutWrapping();
        return [
            'id'                    => $this->id, // neede by SIG Calendar vue component
            'name_localized'        => $this->name_localized,
            'description_localized' => $this->description_localized,
        ];
    }
}
