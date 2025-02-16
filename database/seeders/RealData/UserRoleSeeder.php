<?php

namespace Database\Seeders\RealData;

use App\Enums\Permission;
use App\Enums\PermissionLevel;
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
            'name'                      => 'Administrator',
            'fore_color'                => '#333333',
            'border_color'              => '#2196F3',
            'background_color'          => '#DDFFFF',
        ]);
        foreach(Permission::cases() AS $permission) {
            UserRole::whereName('Administrator')->first()->permissions()->create([
                'permission' => $permission,
                'level' => PermissionLevel::ADMIN,
            ]);
        }


        DB::table('user_roles')->insert([
            'name'                      => 'Leitstelle',
            'name_en'                   => 'Con Ops',
            'registration_system_key'   => 'leitstelle',
        ]);

        foreach([
            Permission::MANAGE_EVENTS,
            Permission::MANAGE_FORMS,
            Permission::MANAGE_POSTS,
            Permission::MANAGE_HOSTS
        ] AS $permission) {
            UserRole::whereName('Leitstelle')->first()->permissions()->create([
                'permission' => $permission,
                'level' => PermissionLevel::ADMIN,
            ]);
        }

        DB::table('user_roles')->insert([
            'name'                      => 'Programmplanung',
            'name_en'                   => 'Programming',
            'registration_system_key'   => 'programming',
            'chat_activated'            => 1,
        ]);

        foreach([
            Permission::MANAGE_EVENTS,
            Permission::MANAGE_FORMS,
            Permission::MANAGE_HOSTS,
            Permission::MANAGE_LOCATIONS,
        ] AS $permission) {
            UserRole::whereName('Programmplanung')->first()->permissions()->create([
                'permission' => $permission,
                'level' => PermissionLevel::ADMIN,
            ]);
        }


        DB::table('user_roles')->insert([
            'name'                      => 'Social Media',
            'registration_system_key'   => 'socialmedia',
        ]);

        UserRole::whereName('Social Media')->first()->permissions()->create([
            'permission' => Permission::MANAGE_POSTS,
            'level' => PermissionLevel::ADMIN,
        ]);
    }


    public static function assignUserToRole($userName, $roleName): void
    {
        $userId = User::where('name', $userName)->first()->id ?? null;
        $roleId = UserRole::where('name', $roleName)->first()->id ?? null;
        if (!$userId || !$roleId) {
            return;
        }
        DB::table('user_user_roles')->insert([
            'user_id' => $userId,
            'user_role_id' => $roleId,
        ]); }
}
