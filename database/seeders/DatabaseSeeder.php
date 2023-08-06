<?php

namespace Database\Seeders;

use App\Models\PostChannel;
use App\Models\SigEvent;
use App\Models\SigHost;
use App\Models\SigLocation;
use App\Models\SigTranslation;
use App\Models\TimetableEntry;
use App\Models\User;
use Database\Factories\TimeTableEntryFactory;
use Database\Factories\SigTranslationFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        (new UserRoleSeeder())->run();
        User::factory()->create([
            'name' => "Kidran",
            'email' => "mail@kidran.de",
            'user_role_id' => 1,
        ]);

        User::factory()->create([
            'name' => "Kenthanar",
            'email' => "kenthanar@sachsenfurs.de",
            'user_role_id' => 1,
        ]);

        //SigLocation::factory()->count(12)->create();
        (new RingbergSeeder())->run();

        SigHost::factory()->count(10)->create();

        SigEvent::factory()->count(25)->create();

        foreach(SigEvent::all() AS $event) {
            SigTranslation::factory()->create([
                'sig_event_id' => $event->id,
            ]);
        }

        TimetableEntry::factory()->count(50)->create();
    }
}
