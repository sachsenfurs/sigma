<?php

namespace App\Http\Resources;

use App\Http\Resources\Api\LocationApiResource;
use App\Http\Resources\Api\SigTagApiResource;
use App\Models\SigEvent;
use App\Models\SigLocation;
use App\Models\TimetableEntry;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class SigEventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /**
         * @var $this SigEvent
         */

        return [
            'name_localized'        => $this->name_localized,
            'description_localized' => $this->description_localized,
            'languages'             => $this->languages,
            'sig_hosts'             => $this->sigHosts->where("hide", false)->setVisible(['name', 'avatar', 'avatar_thumb']),
            'sig_tags'              => $this->sigTags->setVisible(['name', 'description_localized', 'icon'])->sort(),
            'is_private'            => $this->is_private,
        ];
    }
}
