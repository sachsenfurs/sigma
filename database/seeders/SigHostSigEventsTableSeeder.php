<?php

namespace Database\Seeders;

use App\Models\SigEvent;
use App\Models\SigHost;
use DB;
use Illuminate\Database\Seeder;

class SigHostSigEventsTableSeeder extends Seeder
{
    private const MAX_EVENTS_PER_HOST = 5;

    public function run(): void {

        $eventIds = SigEvent::all()->pluck('id')->toArray();
        $hostIds  = SigHost::all()->pluck('id')->toArray();

        foreach ($eventIds as $eventId) {
            $numHosts = rand(1, self::MAX_EVENTS_PER_HOST);
            $chosenHostIds = array_rand($hostIds, $numHosts);
            if (!is_array($chosenHostIds)) {
                // array_rand gibt bei einem einzelnen Wert kein Array zurück
                $chosenHostIds = [$chosenHostIds];
            }

            foreach ($chosenHostIds as $hostId) {
                DB::table('sig_host_sig_events')->insert([
                    'sig_host_id' => $hostIds[$hostId],
                    'sig_event_id' => $eventId,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }
}
