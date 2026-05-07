<?php

namespace Tests\Support\Filament;

use App\Enums\Approval;
use App\Enums\Permission;
use App\Enums\PermissionLevel;
use App\Filament\Pages\SendMassMail;
use App\Filament\Pages\Settings\AppSettingsPage;
use App\Filament\Pages\Settings\ArtShowSettingsPage;
use App\Filament\Pages\Settings\ChatSettingsPage;
use App\Filament\Pages\Settings\DealerSettingsPage;
use App\Filament\Pages\Settings\PageHookSettingsPage;
use App\Filament\Pages\SigPlanner;
use App\Models\Chat;
use App\Models\DepartmentInfo;
use App\Models\Ddas\ArtshowArtist;
use App\Models\Ddas\ArtshowBid;
use App\Models\Ddas\ArtshowItem;
use App\Models\Ddas\ArtshowPickup;
use App\Models\Ddas\Dealer;
use App\Models\Ddas\DealerTag;
use App\Models\Info\Social;
use App\Models\LostFoundItem;
use App\Models\NotificationRoute;
use App\Models\PageHook;
use App\Models\Post\Post;
use App\Models\Post\PostChannel;
use App\Models\SigEvent;
use App\Models\SigForm;
use App\Models\SigHost;
use App\Models\SigLocation;
use App\Models\SigTag;
use App\Models\SigTimeslot;
use App\Models\TimetableEntry;
use App\Models\User;
use App\Models\UserRole;
use App\Models\UserRolePermission;
use App\Settings\AppSettings;
use App\Settings\ArtShowSettings;
use App\Settings\ChatSettings;
use App\Settings\DealerSettings;
use App\Settings\PageHookSettings;
use Database\Factories\ChatFactory;
use Database\Factories\DepartmentInfoFactory;
use Database\Factories\Ddas\ArtshowBidFactory;
use Database\Factories\Ddas\ArtshowPickupFactory;
use Database\Factories\Ddas\DealerTagFactory;
use Database\Factories\Info\SocialFactory;
use Database\Factories\LostFoundItemFactory;
use Database\Factories\NotificationRouteFactory;
use Database\Factories\PageHookFactory;
use Database\Factories\Post\PostChannelFactory;
use Database\Factories\Post\PostFactory;
use Database\Factories\SigFormFactory;
use Database\Factories\SigTagFactory;
use Database\Factories\UserRoleFactory;
use Filament\Facades\Filament;
use Filament\Pages\Page as FilamentPage;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ManageRecords;
use Filament\Resources\Pages\Page as ResourcePage;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Livewire\Livewire;
use ReflectionClass;
use Tests\TestCase;

class FilamentTestSupport
{
    public static function actingAsAdmin(TestCase $testCase): User
    {
        $user = self::createSuperAdmin();

        self::enableFeatureSettings();
        Filament::setCurrentPanel(Filament::getPanel('admin'));
        $testCase->actingAs($user);

        return $user;
    }

    public static function createSuperAdmin(): User
    {
        return Model::withoutEvents(function (): User {
            $user = User::factory()->create();
            $role = UserRoleFactory::new()->create([
                'name' => 'Super Admin',
                'name_en' => 'Super Admin',
            ]);

            UserRolePermission::create([
                'user_role_id' => $role->id,
                'permission' => Permission::MANAGE_ADMIN,
                'level' => PermissionLevel::ADMIN,
            ]);

            $user->roles()->attach($role);

            return $user->fresh();
        });
    }

    public static function enableFeatureSettings(): void
    {
        $app = app(AppSettings::class);
        $app->lost_found_enabled = true;
        $app->telegram_bot_token = null;
        $app->telegram_bot_name = null;
        $app->save();

        $dealer = app(DealerSettings::class);
        $dealer->enabled = true;
        $dealer->image_mandatory = false;
        $dealer->paid_only = false;
        $dealer->save();

        $artshow = app(ArtShowSettings::class);
        $artshow->enabled = true;
        $artshow->paid_only = false;
        $artshow->require_checkin = false;
        $artshow->save();

        $chat = app(ChatSettings::class);
        $chat->enabled = true;
        $chat->save();

        $pageHooks = app(PageHookSettings::class);
        $pageHooks->show_in_source = false;
        $pageHooks->save();
    }

    public static function customPages(): array
    {
        return [
            'send-mass-mail' => [SendMassMail::class, []],
            'sig-planner' => [SigPlanner::class, []],
            'settings-app' => [AppSettingsPage::class, []],
            'settings-artshow' => [ArtShowSettingsPage::class, []],
            'settings-chat' => [ChatSettingsPage::class, []],
            'settings-dealer' => [DealerSettingsPage::class, []],
            'settings-pagehooks' => [PageHookSettingsPage::class, []],
        ];
    }

    public static function resourcePages(): array
    {
        $pages = [];
        $deniedResources = [
            \App\Filament\Resources\Ddas\ArtshowPickups\ArtshowPickupResource::class,
        ];

        foreach (File::allFiles(app_path('Filament/Resources')) as $file) {
            $class = self::classFromPath($file->getRealPath());

            if (! str_contains($class, '\\Pages\\')) {
                continue;
            }

            if (str_contains($class, '\\Shifts\\') || str_contains($class, '\\ShiftTypes\\')) {
                continue;
            }

            if (! class_exists($class) || ! is_subclass_of($class, ResourcePage::class)) {
                continue;
            }

            $params = [];
            $record = null;
            $expectsAccess = true;

            $resourceClass = self::getPageResourceClass($class);

            if (in_array($resourceClass, $deniedResources, true)) {
                $expectsAccess = false;
            }

            if (
                is_subclass_of($class, ListRecords::class) ||
                is_subclass_of($class, ManageRecords::class) ||
                is_subclass_of($class, EditRecord::class) ||
                is_subclass_of($class, ViewRecord::class)
            ) {
                $modelClass = self::getResourceModel($class);
                $record = self::createRecordForModel($modelClass);
                if (is_subclass_of($class, EditRecord::class) || is_subclass_of($class, ViewRecord::class)) {
                    $params = ['record' => $record->getRouteKey()];
                }
            }

            $pages[$class] = [$class, $params, $record, $expectsAccess];
        }

        ksort($pages);

        return $pages;
    }

    public static function createRecordForModel(string $modelClass): Model
    {
        return Model::withoutEvents(fn (): Model => match ($modelClass) {
            User::class => User::factory()->create(),
            UserRole::class => UserRoleFactory::new()->create(),
            SigTag::class => SigTagFactory::new()->create(),
            SigLocation::class => SigLocation::factory()->create(),
            SigHost::class => SigHost::factory()->create([
                'reg_id' => User::factory()->create()->reg_id,
            ]),
            SigEvent::class => SigEvent::factory()->create([
                'private_group_ids' => null,
            ]),
            SigForm::class => SigFormFactory::new()->create(),
            TimetableEntry::class => TimetableEntry::factory()->create([
                'sig_event_id' => self::createRecordForModel(SigEvent::class)->getKey(),
                'sig_location_id' => self::createRecordForModel(SigLocation::class)->getKey(),
                'hide' => false,
                'cancelled' => false,
                'new' => false,
            ]),
            SigTimeslot::class => SigTimeslot::query()->create([
                'timetable_entry_id' => self::createRecordForModel(TimetableEntry::class)->getKey(),
                'max_users' => 5,
                'slot_start' => now()->addDay(),
                'slot_end' => now()->addDay()->addHour(),
                'reg_start' => now()->subDay(),
                'reg_end' => now()->addDay(),
                'self_register' => true,
            ]),
            DepartmentInfo::class => DepartmentInfoFactory::new()->create(),
            Social::class => SocialFactory::new()->create(),
            LostFoundItem::class => LostFoundItemFactory::new()->create(),
            DealerTag::class => DealerTagFactory::new()->create(),
            Dealer::class => Dealer::factory()->create([
                'sig_location_id' => self::createRecordForModel(SigLocation::class)->getKey(),
                'icon_file' => null,
                'approval' => Approval::APPROVED->value,
            ]),
            ArtshowArtist::class => ArtshowArtist::factory()->create(),
            ArtshowItem::class => ArtshowItem::factory()->create([
                'artshow_artist_id' => self::createRecordForModel(ArtshowArtist::class)->getKey(),
                'image' => null,
                'approval' => Approval::APPROVED->value,
            ]),
            ArtshowBid::class => ArtshowBidFactory::new()->create([
                'artshow_item_id' => self::createRecordForModel(ArtshowItem::class)->getKey(),
                'user_id' => self::createRecordForModel(User::class)->getKey(),
            ]),
            ArtshowPickup::class => ArtshowPickupFactory::new()->create([
                'artshow_item_id' => self::createRecordForModel(ArtshowItem::class)->getKey(),
                'user_id' => self::createRecordForModel(User::class)->getKey(),
            ]),
            Chat::class => ChatFactory::new()->create([
                'user_id' => self::createRecordForModel(User::class)->getKey(),
                'user_role_id' => self::createRecordForModel(UserRole::class)->getKey(),
            ]),
            Post::class => PostFactory::new()->create([
                'user_id' => self::createRecordForModel(User::class)->getKey(),
            ]),
            PostChannel::class => PostChannelFactory::new()->create(),
            NotificationRoute::class => NotificationRouteFactory::new()->create([
                'notifiable_id' => self::createRecordForModel(User::class)->getKey(),
            ]),
            PageHook::class => PageHookFactory::new()->create(),
            default => throw new \RuntimeException("No record builder configured for {$modelClass}."),
        });
    }

    public static function mountPage(string $pageClass, array $params = [])
    {
        return Livewire::test($pageClass, $params)->assertStatus(200);
    }

    private static function getResourceModel(string $pageClass): string
    {
        $resourceClass = self::getPageResourceClass($pageClass);
        $resourceReflection = new ReflectionClass($resourceClass);
        $modelProperty = $resourceReflection->getProperty('model');
        $modelProperty->setAccessible(true);

        return $modelProperty->getValue();
    }

    private static function getPageResourceClass(string $pageClass): string
    {
        $reflection = new ReflectionClass($pageClass);
        $resourceProperty = $reflection->getProperty('resource');
        $resourceProperty->setAccessible(true);

        return $resourceProperty->getValue();
    }

    private static function classFromPath(string $path): string
    {
        $relative = Str::after($path, app_path() . DIRECTORY_SEPARATOR);
        $relative = str_replace([DIRECTORY_SEPARATOR, '.php'], ['\\', ''], $relative);

        return 'App\\' . $relative;
    }
}
