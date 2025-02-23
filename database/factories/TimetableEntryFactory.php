<?php

namespace Database\Factories;

use App\Models\SigEvent;
use App\Models\SigLocation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class TimetableEntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $start = $this->faker->dateTimeBetween(Carbon::yesterday(), Carbon::tomorrow()->addDay(2));
        $start = (new Carbon($start))->setMinutes($this->faker->randomElement([0,15,30,45]))->setSeconds(0);
        return [
            'sig_event_id' => SigEvent::all()->random(),
            'sig_location_id' => SigLocation::all()->random(),
            'start' => $start,
            'end' => (new Carbon($start))->addMinute(90),
            'cancelled' => false,
            'replaced_by_id' => null,
        ];
    }
}
