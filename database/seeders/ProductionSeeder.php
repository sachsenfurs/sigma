<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserRole;
use Database\Seeders\RealData\EASTSeeder;
use Database\Seeders\RealData\RingbergSeeder;
use Database\Seeders\RealData\SigTagSeeder;
use Database\Seeders\RealData\UserRoleSeeder;
use Illuminate\Database\Seeder;

class ProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new UserRoleSeeder())->run();

        User::factory()->create([
            'name' => "Kidran",
            'reg_id' => 1,
            'email' => "mail@kidran.de"
        ])->roles()->attach(UserRole::where("name", "Administrator")->first());

        (new RingbergSeeder())->run();
        (new EASTSeeder())->run();

        (new SigTagSeeder())->run();
    }
}
