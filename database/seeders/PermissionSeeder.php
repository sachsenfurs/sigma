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

            // Laravel & Filament permissions
            'view' => 'View data',
            'viewAny' => 'View any data',
            'create' => 'Create data',
            'createAny' => 'Create any data',
            'update' => 'Update data',
            'updateAny' => 'Update any data',
            'delete' => 'Delete data',
            'deleteAny' => 'Delete any data',
            'forceDelete' => 'Force delete data',
            'forceDeleteAny' => 'Force delete any data',
            'restore' => 'Restore data',
            'restoreAny' => 'Restore any data',
            'attach' => 'Attach data',
            'attachAny' => 'Attach any data',
            'detach' => 'Detach data',
            'detachAny' => 'Detach any data',
            'reorder' => 'Reorder data',
            'replicate' => 'Replicate data',
            'associate' => 'Associate data',
            'associateAny' => 'Associate any data',
            'dissociate' => 'Dissociate data',
            'dissociateAny' => 'Dissociate any data',
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
