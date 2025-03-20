<?php

namespace App\Http\Resources;

use App\Models\TimetableEntry;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class ScheduleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        self::withoutWrapping();
        /**
         * @var $this TimetableEntry
         */

        return [
            'id'                    => $this->id,
            'start'                 => Carbon::parse($this->start)->toW3cString(),
            'end'                   => Carbon::parse($this->end)->toW3cString(),
            'cancelled'             => $this->cancelled,
            'hide'                  => $this->hide,
            'new'                   => $this->new,
            'formatted_length'      => $this->formatted_length,
            'hasTimeChanged'        => $this->has_time_changed,
            'hasLocationChanged'    => $this->has_location_changed,
            'is_favorite'           => $this->is_favorite,
            'sig_location'          => SigLocationResource::make($this->sigLocation),
            'sig_event'             => SigEventResource::make($this->sigEvent),

            'eventColor'            => $this->eventColor,
        ];
    }
}
