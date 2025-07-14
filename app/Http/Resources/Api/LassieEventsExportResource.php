<?php

namespace App\Http\Resources\Api;

use App\Models\TimetableEntry;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LassieEventsExportResource extends JsonResource
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
            //title	varchar(250)	NO	<empty string>	 Title of the event
            'title' => $this->sigEvent->name,

            //start	datetime	NO	current_timestamp()	 ISO-Format, UTC time (i.e. "2019-08-01 11:30:00" for an event at 13:30 in Berlin in summer GMT+2)
            'start' => $this->start->timezone("UTC")->toDateTimeString(),

            //end	datetime	NO	current_timestamp()	 ISO-Format, UTC time (i.e. "2019-08-01 11:30:00" for an event at 13:30 in Berlin in summer GMT+2)
            'end' => $this->end->timezone("UTC")->toDateTimeString(),

            // allDay	int(1)	YES	NULL	 If the event lasts all day, then "1"
            'allDay' => intval($this->start->diffInHours($this->end) >= 24),

            // internal	int(1)	YES	"1" if the event is internal (e.g. buildup times), can be empty
            'internal' => intval($this->hide),

            //location	varchar(250)	YES	NULL	 Location of the event
            'location' => $this->sigLocation->name,

            //text	text	YES	NULL	Conbook Text, further information about the event
            'text' => $this->sigEvent->description,

            //original_id	varchar(255)	YES	NULL	The reference number from the individual event planning software, i.e. Pentabarf. Needed for automatic event updating.
            'original_id' => $this->id,
        ];
    }
}
