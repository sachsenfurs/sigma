<?php

namespace Database\Factories;

use App\Models\SigEvent;
use App\Models\SigLocation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class TimeTableEntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $start = $this->faker->dateTimeBetween("2022-07-15 00:00:00", "2022-07-24 13:00:00");
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