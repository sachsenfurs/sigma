<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\User;
use App\Models\UserRole;
use DB;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('user_roles')->insert([
            'title'                     => 'Administrator',
            'fore_color'                => '#333333',
            'border_color'              => '#2196F3',
            'background_color'          => '#DDFFFF',
            'created_at'                => now()
        ]);
        $this->insertPermissions('Administrator', Permission::all()->pluck('name')->toArray());

        DB::table('user_roles')->insert([
            'title'                     => 'Gast',
            'created_at'                => now()
        ]);

        DB::table('user_roles')->insert([
            'title'                     => 'Leitstelle',
            'registration_system_key'   => 'leitstelle,foto',
            'created_at'                => now()
        ]);
        $this->insertPermissions('Leitstelle', [
            'manage_events',
            'manage_sig_base_data',
            'manage_sigs',
        ]);

        DB::table('user_roles')->insert([
            'title'                     => 'Social Media',
            'registration_system_key'   => 'socialmedia',
            'created_at'                => now()
        ]);
        $this->insertPermissions('Social Media', [
            'post',
        ]);
    }

    private function insertPermissions(string $roleName, array $permissionNames): void
    {
        $roleId = UserRole::where('title', $roleName)->first()->id ?? null;
        if (!$roleId) {
            return;
        }
        foreach ($permissionNames as $permissionName) {
            $permissionId = Permission::where('name', $permissionName)->first()->id ?? null;
            if (!$permissionId) {
                continue;
            }
            DB::table('user_role_permissions')->insert([
                'user_role_id' => $roleId,
                'permission_id' => $permissionId,
                'created_at' => now()
            ]);
        }
    }

    public static function assignUserToRole($userName, $roleName): void
    {
        $userId = User::where('name', $userName)->first()->id ?? null;
        $roleId = UserRole::where('title', $roleName)->first()->id ?? null;
        if (!$userId || !$roleId) {
            return;
        }
        DB::table('user_user_roles')->insert([
            'user_id' => $userId,
            'user_role_id' => $roleId,
            'created_at' => now()
        ]); }
}
