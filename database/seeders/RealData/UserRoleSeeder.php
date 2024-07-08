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
            'title'                     => 'Administrator',
            'fore_color'                => '#333333',
            'border_color'              => '#2196F3',
            'background_color'          => '#DDFFFF',
        ]);
        foreach(Permission::cases() AS $permission) {
            UserRole::whereTitle('Administrator')->first()->permissions()->create([
                'permission' => $permission,
                'level' => PermissionLevel::ADMIN,
            ]);
        }

        DB::table('user_roles')->insert([
            'title'                     => 'Gast',
        ]);

        DB::table('user_roles')->insert([
            'title'                     => 'Leitstelle',
            'registration_system_key'   => 'leitstelle',
        ]);
        foreach([
            Permission::MANAGE_EVENTS,
            Permission::MANAGE_FORMS,
            Permission::MANAGE_POSTS,
            Permission::MANAGE_HOSTS
                ] AS $permission) {
            UserRole::whereTitle('Leitstelle')->first()->permissions()->create([
                'permission' => $permission,
                'level' => PermissionLevel::ADMIN,
            ]);
        }


        DB::table('user_roles')->insert([
            'title'                     => 'Social Media',
            'registration_system_key'   => 'socialmedia',
        ]);

        UserRole::whereTitle('Social Media')->first()->permissions()->create([
            'permission' => Permission::MANAGE_POSTS,
            'level' => PermissionLevel::ADMIN,
        ]);
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
        ]); }
}
