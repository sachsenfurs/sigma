<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        User::factory()->create([
            'name' => "Kidran",
            'email' => "mail@kidran.de",
            'is_admin' => true,
        ]);

        (new RingbergSeeder())->run();
    }
}
