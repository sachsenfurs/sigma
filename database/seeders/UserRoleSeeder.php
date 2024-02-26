<?php

namespace Database\Seeders;

use \DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_roles')->insert([
            'title'                 => 'Administrator',
            'fore_color'            => '#333333',
            'border_color'          => '#2196F3',
            'background_color'      => '#DDFFFF',
            'perm_manage_settings'  => true,
            'perm_manage_users'     => true,
            'perm_manage_events'    => true,
            'perm_manage_locations' => true,
            'perm_manage_hosts'     => true,
            'created_at'            => now()
        ]);

        DB::table('user_roles')->insert([
            'title'                 => 'Gast',
            'created_at'            => now()
        ]);

        DB::table('user_roles')->insert([
            'title'                 => 'Leitstelle',
            'perm_manage_events'    => true,
            'created_at'            => now()
        ]);
        DB::table('user_roles')->insert([
            'title'                 => 'Social Media',
            'perm_post'             => true,
            'created_at'            => now()
        ]);
    }
}
