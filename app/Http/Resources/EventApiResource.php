<?php

namespace App\Http\Resources;

use App\Models\TimetableEntry;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class EventApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /**
         * @var $this TimetableEntry
         */
        return [
            'id'                    => $this->id,
            'name'                  => $this->sigEvent->name,
            'name_en'               => $this->sigEvent->name_en,
            'host'                  => $this->sigEvent->primaryHost->hide ? false : $this->sigEvent->primaryHost,
            'start'                 => Carbon::parse($this->start)->toW3cString(),
            'end'                   => Carbon::parse($this->end)->toW3cString(),
            'description'           => $this->sigEvent->description,
            'description_en'        => $this->sigEvent->description_en,
            'languages'             => $this->sigEvent->languages,
            'location'              => LocationApiResource::make($this->sigLocation),
            'time_changed'          => $this->hasTimeChanged,
            'location_changed'      => $this->hasLocationChanged,
            'cancelled'             => $this->cancelled,
            'tags'                  => SigTagApiResource::collection($this->sigEvent->sigTags),
        ];
    }
}
