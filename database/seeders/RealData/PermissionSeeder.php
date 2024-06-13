<?php

namespace Database\Seeders\RealData;

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
            'manage_settings' => 'Manage settings (Legacy)',
            'manage_users' => 'Manage users (Legacy & Filament)',
            'manage_events' => 'Manage events (Legacy)',
            'manage_locations' => 'Manage locations (Legacy)',
            'manage_hosts' => 'Manage hosts (Legacy)',
            'post' => 'Manage posts (Legacy)',
            'login' => 'Login',

            // Permissions for filament
            'manage_sig_base_data' => 'Manage SIG base data (Hosts, Tags, Locations)',
            'manage_sigs' => 'Manage SIGs (SIGs, Timetable)',
            'manage_artshow' => 'Manage artshow (Artists, Commandments, Items, Pickups)',
            'manage_dealers_den' => 'Manage dealers den (Dealer, Dealer Tags)',
            'manage_forms' => 'Manage dynamic forms (Forms)',
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
