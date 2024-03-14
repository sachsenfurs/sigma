<?php

namespace Database\Seeders;

use App\Models\DDAS\ArtshowItem;
use App\Models\DDAS\Dealer;
use App\Models\PostChannel;
use App\Models\SigEvent;
use App\Models\SigHost;
use App\Models\SigLocation;
use App\Models\SigTranslation;
use App\Models\TimetableEntry;
use App\Models\User;
use Database\Factories\TimetableEntryFactory;
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

        TimetableEntry::factory()->count(50)->create();

        ArtshowItem::factory(10)->create();
        Dealer::factory(10)->create();
    }
}
