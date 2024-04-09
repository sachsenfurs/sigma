<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $permission = [
            'manage_settings' => 'Manage settings',
            'manage_users' => 'Manage users',
            'manage_events' => 'Manage events',
            'manage_locations' => 'Manage locations',
            'manage_hosts' => 'Manage hosts',
            'post' => 'Manage posts',
            'login' => 'Login',

            // Permissions for filament
            'manage_sig_base_data' => 'Manage SIG base data (Hosts, Tags, Locations)',
            'manage_sigs' => 'Manage SIGs (SIGs, Timetable)',
            'manage_artshow' => 'Manage artshow (Artists, Commandments, Items, Pickups)',
            'manage_dealers_den' => 'Manage dealers den (Dealer, Dealer Tags)',
        ];

        foreach ($permission as $name => $friendly_name) {
            Permission::create([
                'name' => $name,
                'friendly_name' => $friendly_name,
                'created_at' => now(),
            ]);
        }
    }
}
