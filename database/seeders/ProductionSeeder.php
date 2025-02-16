<?php

namespace Database\Seeders;

use App\Models\User;
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

        User::create([
            'name' => "Kidran",
            'email' => "mail@kidran.de",
            'reg_id' => 1,
        ]);
        UserRoleSeeder::assignUserToRole('Kidran', 'Administrator');

        (new RingbergSeeder())->run();
        (new EASTSeeder())->run();

        (new SigTagSeeder())->run();
    }
}
