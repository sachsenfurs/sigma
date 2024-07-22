<?php

namespace App\Http\Resources;

use App\Http\Resources\Api\LocationApiResource;
use App\Http\Resources\Api\SigTagApiResource;
use App\Models\SigLocation;
use App\Models\TimetableEntry;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

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
            'id'                    => $this->id,
            'name_localized'        => $this->name_localized,
            'description_localized' => $this->description_localized,
        ];
    }
}
