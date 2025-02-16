<?php

namespace Database\Seeders\RealData;

use App\Enums\Permission;
use App\Enums\PermissionLevel;
use App\Facades\NotificationService;
use App\Models\UserRole;
use App\Notifications\Ddas\ArtshowItemSubmittedNotification;
use App\Notifications\Sig\NewSigApplicationNotification;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void {
        UserRole::create([
            'name'                      => 'Administration',
            'name_en'                   => 'Administration',
            'fore_color'                => '#333333',
            'border_color'              => '#8a0303',
            'background_color'          => '#b54343',
        ])->permissions()->createMany(collect(Permission::cases())->map(fn($p) => [
            'permission' => $p->name,
            'level' => PermissionLevel::ADMIN->value,
        ]));


        UserRole::create([
            'name'                      => 'Leitstelle',
            'name_en'                   => 'Con Ops',
            'fore_color'                => '#222222',
            'registration_system_key'   => 'leitstelle',
            'border_color'              => '#8a0303',
            'background_color'          => '#f06767',
        ])->permissions()->createMany(collect([
            Permission::MANAGE_EVENTS,
            Permission::MANAGE_FORMS,
            Permission::MANAGE_POSTS,
            Permission::MANAGE_HOSTS
        ])->map(fn($p) => [
            'permission' => $p->name,
            'level' => PermissionLevel::ADMIN->value,
        ]));


        tap(UserRole::create([
            'name'                      => 'Programmplanung',
            'name_en'                   => 'Programming',
            'fore_color'                => '#111111',
            'registration_system_key'   => 'programming',
            'border_color'              => '#8a0303',
            'background_color'          => '#dea95b',
            'chat_activated'            => 1,
        ]), function($role) {
            $role->permissions()->createMany(
                collect([
                    Permission::MANAGE_EVENTS,
                    Permission::MANAGE_FORMS,
                    Permission::MANAGE_HOSTS,
                    Permission::MANAGE_LOCATIONS,
                ])->map(fn($p) => [
                    'permission' => $p->name,
                    'level' => PermissionLevel::ADMIN->value,
                ])
            );
        })->notificationRoutes()->create([
            'notification' => NotificationService::morphName(NewSigApplicationNotification::class),
            'channels' => NotificationService::availableChannels(),
        ]);


        UserRole::create([
            'name'                      => 'Social Media',
            'name_en'                   => 'Social Media',
            'fore_color'                => '#111111',
            'registration_system_key'   => 'socialmedia',
            'border_color'              => '#04465e',
            'background_color'          => '#5499b3',
        ])->permissions()->createMany(collect([
            Permission::MANAGE_POSTS
        ])->map(fn($p) => [
            'permission' => $p->name,
            'level' => PermissionLevel::DELETE->value,
        ]));

        tap(UserRole::create([
            'name'                      => 'Art Show & Dealers Den',
            'name_en'                   => "Art Show & Dealers' Den",
            'fore_color'                => '#111111',
            'registration_system_key'   => 'artshow',
            'border_color'              => '#4d0d54',
            'background_color'          => '#9a33a6',
            'chat_activated'            => 1,
        ]), function ($role) {
            $role->permissions()->createMany(
                collect([
                    Permission::MANAGE_ARTSHOW,
                    Permission::MANAGE_DEALERS,
                ])->map(fn($p) => [
                    'permission' => $p->name,
                    'level' => PermissionLevel::ADMIN->value,
                ])
            );
        })->notificationRoutes()->create([
            'notification' => NotificationService::morphName(ArtshowItemSubmittedNotification::class),
            'channels' => NotificationService::availableChannels(),
        ]);


        UserRole::create([
            'name'                      => 'Staff',
            'name_en'                   => "Staff",
            'fore_color'                => '#111111',
            'registration_system_key'   => 'staff',
            'border_color'              => '#75560c',
            'background_color'          => '#967933',
        ])->permissions()->createMany(collect([
            Permission::MANAGE_EVENTS,
            Permission::MANAGE_HOSTS,
            Permission::MANAGE_LOCATIONS,
        ])->map(fn($p) => [
            'permission' => $p->name,
            'level' => PermissionLevel::READ->value,
        ]));

    }

}
