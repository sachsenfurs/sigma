<?php

namespace Database\Seeders;

use App\Models\Ddas\ArtshowItem;
use App\Models\Ddas\Dealer;
use App\Models\Ddas\DealerTag;
use App\Models\SigEvent;
use App\Models\SigHost;
use App\Models\TimetableEntry;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        (new PermissionSeeder())->run(); // Needs to be run before user role seeder
        (new UserRoleSeeder())->run();

        User::factory()->create([
            'name' => "Kidran",
            'email' => "mail@kidran.de"
        ]);
        UserRoleSeeder::assignUserToRole('Kidran', 'Administrator');

        User::factory()->create([
            'name' => "Kenthanar",
            'email' => "kenthanar@sachsenfurs.de"
        ]);
        UserRoleSeeder::assignUserToRole('Kenthanar', 'Administrator');

        User::factory()->create([
            'name' => "Lytrox",
            'email' => "lytrox@sachsenfurs.de"
        ]);
        UserRoleSeeder::assignUserToRole('Lytrox', 'Administrator');

        //SigLocation::factory()->count(12)->create();
        (new RingbergSeeder())->run();

        SigHost::factory()->count(10)->create();

        SigEvent::factory()->count(25)->create();

        TimetableEntry::factory()->count(50)->create();

        ArtshowItem::factory(10)->create();

        DealerTag::insert([
            [
                'name' => "NSFW",
                'name_en' => "NSFW",
            ],
            [
                'name' => "Merch",
                'name_en' => "Merch",
            ],
            [
                'name' => "Prints",
                'name_en' => "Prints",
            ],
            [
                'name' => "Plushies",
                'name_en' => "Plushies",
            ]
        ]);

        Dealer::factory(15)->create();

        foreach(Dealer::all() AS $dealer) {
            $dealer->tags()->sync(DealerTag::inRandomOrder()->limit(rand(0,3))->get());
        }

    }
}
