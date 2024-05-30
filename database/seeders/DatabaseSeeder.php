<?php

namespace Database\Seeders;

use App\Models\Ddas\ArtshowItem;
use App\Models\SigEvent;
use App\Models\SigHost;
use App\Models\SigTag;
use App\Models\TimetableEntry;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\RealData\EASTSeeder;
use Database\Seeders\RealData\PermissionSeeder;
use Database\Seeders\RealData\RingbergSeeder;
use Database\Seeders\RealData\SigTagSeeder;
use Database\Seeders\RealData\UserRoleSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with TESTING DATA
     *
     * @return void
     */
    public function run(): void
    {
        (new PermissionSeeder())->run(); // Needs to be run before user role seeder
        (new UserRoleSeeder())->run();

        User::factory()->create([
            'name' => "Kidran",
            'reg_id' => 1,
            'email' => "mail@kidran.de"
        ]);
        UserRoleSeeder::assignUserToRole('Kidran', 'Administrator');

        User::factory()->create([
            'name' => "Lytrox",
            'reg_id' => 2,
            'email' => "lytrox@sachsenfurs.de"
        ]);
        UserRoleSeeder::assignUserToRole('Lytrox', 'Administrator');

        (new RingbergSeeder())->run();
        (new EASTSeeder())->run();

        (new PostChannelSeeder())->run();

        SigHost::factory()->count(10)->create();
        SigEvent::factory()->count(25)->create();

         // create tags and associate random sigs
        (new SigTagSeeder())->run();
        $sigs = SigEvent::inRandomOrder()->limit(10);
        $sigs->each(function($sig) {
            /**
             * @var $sig SigEvent
             */
            $sig->sigTags()->attach(SigTag::inRandomOrder()->limit(rand(0,3))->get());
        });


        TimetableEntry::factory()->count(50)->create();
        $entries = TimetableEntry::inRandomOrder()->limit(15)->get();
        $entries->each(function($entry) {
            /**
             * @var $entry TimetableEntry
             */
            $entry->sigTimeslots()->create([
                'max_users' => rand(1,5),
                'slot_start' => $entry->start,
                'slot_end' => $entry->end,
                'reg_start' => Carbon::now(),
                'reg_end' => Carbon::now()->addDays(7),
                'description' => "Slot Description",
            ]);
            $entry->sigEvent->reg_possible = true;
            $entry->sigEvent->save();
        });

        ArtshowItem::factory(10)->create();
    }
}
