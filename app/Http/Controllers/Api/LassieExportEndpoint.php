<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TimetableEntry;
use App\Settings\AppSettings;
use Illuminate\Support\Facades\Gate;

class LassieExportEndpoint extends Controller
{
    public function index() {
        Gate::denyIf(!request()->hasValidSignature());

        header('Content-Type: text/plain; charset=UTF-8');
        header('Content-Disposition: attachment;filename=Schedule.csv');
        echo "\xEF\xBB\xBF"; // UTF-8 BOM - Excel der hurensohn

        $csvStream = fopen("php://output", "w");
        $first = true;
        foreach(TimetableEntry::orderBy("start")->get() AS $timetableEntry) {
            /**
             * @var $timetableEntry TimetableEntry
             */
            $entry = [
                //id	int(11)	NO	Auto-Increment	 Do include but leave empty!
                'id' => null,

                //title	varchar(250)	NO	<empty string>	 Title of the event
                'title' => $timetableEntry->sigEvent->name,

                //start	datetime	NO	current_timestamp()	 ISO-Format, UTC time (i.e. "2019-08-01 11:30:00" for an event at 13:30 in Berlin in summer GMT+2)
                'start' => $timetableEntry->start->toDateTimeString(),

                //end	datetime	NO	current_timestamp()	 ISO-Format, UTC time (i.e. "2019-08-01 11:30:00" for an event at 13:30 in Berlin in summer GMT+2)
                'end' => $timetableEntry->end->toDateTimeString(),

                //location	varchar(250)	YES	NULL	 Location of the event
                'location' => $timetableEntry->sigLocation->name,

                //url	varchar(250)	YES	NULL	 An URL for the event
                'url' => route("timetable-entry.show", $timetableEntry),

                // allDay	int(1)	YES	NULL	 If the event lasts all day, then "1"
                'allDay' => intval($timetableEntry->start->diffInHours($timetableEntry->end) >= 24),

                //color	varchar(20)	NO	rgb(204, 204, 204)	 Color for the event in the planner. Can be decimal rgb(r, g, b) or hexadecimal #rrggbb
                'color' => "rgb(204, 204, 204)",

                //con_id	int(11)	NO	5	Your Convention's ID. Ask Dingo.
                'con_id' => app(AppSettings::class)->lassie_con_id,

                //con_event_id	int(11)	NO	34	Check System Settings → Events
                'con_event_id' => app(AppSettings::class)->lassie_con_event_id,

                //num_ops	int(2)	NO	2	Used if the event is a security shift. For "MnM" events use "0".
                'num_ops' => 0,

                //text	text	YES	NULL	Conbook Text, further information about the event
                'text' => $timetableEntry->sigEvent->description,

                //original_id	varchar(255)	YES	NULL	The reference number from the individual event planning software, i.e. Pentabarf. Needed for automatic event updating.
                'original_id' => $timetableEntry->id,

                //is_unit	enum('N','Y')	NO	N	Use the string "Y" if the event is a Security shift that should be available as alertable unit (i.e. §S: ODS).
                'is_unit' => "N",

                //ric	int(7) unsigned	NO	0	Only used if the event is a Security shift that should be available as alertable unit (i.e. §S: ODS) and a Pager system is in use.
                'ric' => 0,
            ];
            if($first)
                fputcsv($csvStream, array_keys($entry), ";");

            fputcsv($csvStream, $entry, ";");

            $first = false;
        }


    }
}
