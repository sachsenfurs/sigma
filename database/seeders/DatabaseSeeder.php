<?php

namespace Database\Seeders;

use App\Facades\NotificationService;
use App\Models\Ddas\ArtshowItem;
use App\Models\NotificationRoute;
use App\Models\Post\PostChannel;
use App\Models\SigEvent;
use App\Models\SigFavorite;
use App\Models\SigHost;
use App\Models\SigTag;
use App\Models\TimetableEntry;
use App\Models\User;
use App\Models\UserRole;
use Carbon\Carbon;
use Database\Seeders\RealData\EASTSeeder;
use Database\Seeders\RealData\SigTagSeeder;
use Database\Seeders\RealData\UserRoleSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with TESTING DATA
     *
     * @return void
     */
    public function run(): void {
        Cache::clear();

        (new UserRoleSeeder())->run();

        User::factory()->create([
            'name' => "Kidran",
            'reg_id' => 1,
            'email' => "mail@kidran.de"
        ])->roles()->sync(
            UserRole::find([1, 3, 4])
        );


        User::factory()->create([
            'name' => "Lytrox",
            'reg_id' => 2,
            'email' => "lytrox@sachsenfurs.de"
        ])->roles()->attach(UserRole::where("name", "Administration")->first());

        $users = User::factory()->count(50)->create();

        (new EASTSeeder())->run();

        (new PostChannelSeeder())->run();

        // notification routes
        foreach(PostChannel::all() AS $channel) {
            foreach([
                'event_cancelled',
                'event_changed',
                'event_new'
            ] AS $notification) {
                $channel->notificationRoutes()->create([
                    'notification' => $notification,
                    'channels' => NotificationService::availableChannels(),
                ]);
            }
        }

        // create sig hosts
        foreach($users AS $user) {
            if(rand(0,100) > 10)
                SigHost::factory()->create([
                    'reg_id' => $user->reg_id,
                    'name' => $user->name,
                ]);
        }

        // create sig events
        SigEvent::factory()->count(40)->create();

         // create tags and associate random sigs
        (new SigTagSeeder())->run();
        $sigs = SigEvent::inRandomOrder()->limit(10);
        $sigs->each(function($sig) {
            /**
             * @var $sig SigEvent
             */
            $sig->sigTags()->attach(SigTag::inRandomOrder()->limit(rand(0,3))->get());
            $sig->sigHosts()->attach(SigHost::inRandomOrder()->limit(rand(1,2))->get());
        });

        TimetableEntry::factory()->count(50)->create();
        $entries = TimetableEntry::inRandomOrder()->limit(15)->get();
        $entries->each(function($entry) {
            /**
             * @var $entry TimetableEntry
             */
            $slot = $entry->sigTimeslots()->create([
                'max_users' => rand(1,5),
                'slot_start' => $entry->start,
                'slot_end' => $entry->end,
                'reg_start' => Carbon::now(),
                'reg_end' => Carbon::now()->addDays(7),
                'description' => "Slot Description",
            ]);
            $entry->sigEvent->reg_possible = true;
            $entry->sigEvent->save();
            $slot->sigAttendees()->make()->user()->associate(User::find(1))->save();
        });


        // generate some favorites
        $inserts = collect();
        foreach(TimetableEntry::all() AS $entry) {
            $user_ids = User::inRandomOrder()->limit(rand(0,20))->get("id");
            foreach($user_ids AS $user) {
                $inserts->add([
                    'timetable_entry_id' => $entry->id,
                    'user_id' => $user->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        SigFavorite::insert($inserts->toArray());


        // Art show & dealers den
        ArtshowItem::factory(50)->create();
        (new DealerSeeder())->run();
    }
}
