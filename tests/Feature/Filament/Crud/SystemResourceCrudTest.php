<?php

use App\Enums\ChatStatus;
use App\Filament\Resources\Chats\Pages\EditChat;
use App\Filament\Resources\Chats\Pages\ListChats;
use App\Filament\Resources\LostFoundItems\Pages\ListLostFoundItems;
use App\Filament\Resources\NotificationRoutes\Pages\ListNotificationRoutes;
use App\Filament\Resources\PageHooks\Pages\ManagePageHooks;
use App\Filament\Resources\PostChannels\Pages\ManagePostChannels;
use App\Filament\Resources\Posts\Pages\ManagePosts;
use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Models\Chat;
use App\Models\LostFoundItem;
use App\Models\NotificationRoute;
use App\Models\PageHook;
use App\Models\Post\Post;
use App\Models\Post\PostChannel;
use App\Models\User;
use App\Models\UserRole;
use App\Services\NotificationService;
use Carbon\Carbon;
use Database\Factories\UserRoleFactory;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\Filament\FilamentCrudTestSupport;
use Tests\Support\Filament\FilamentTestSupport;

uses(RefreshDatabase::class);

it('completes CRUD cycles for system resource pages', function () {
    FilamentTestSupport::actingAsAdmin($this);

    $failures = [];

    foreach (pageCrudCases() as $label => $case) {
        try {
            FilamentCrudTestSupport::runPageCrudCycle([
                ...$case,
                'label' => $label,
                'delete_action' => DeleteAction::class,
            ]);
        } catch (\Throwable $throwable) {
            $failures[] = $label . ': ' . $throwable->getMessage();
        }
    }

    expect($failures)->toBeEmpty(implode(PHP_EOL, $failures));
});

it('completes action-driven CRUD cycles for system resources', function () {
    FilamentTestSupport::actingAsAdmin($this);

    $failures = [];

    foreach (actionCrudCases() as $label => $case) {
        try {
            FilamentCrudTestSupport::runListActionCrudCycle([
                ...$case,
                'label' => $label,
            ]);
        } catch (\Throwable $throwable) {
            $failures[] = $label . ': ' . $throwable->getMessage();
        }
    }

    expect($failures)->toBeEmpty(implode(PHP_EOL, $failures));
});

it('creates, updates and deletes chats through list and edit pages', function () {
    FilamentTestSupport::actingAsAdmin($this);

    $user = User::factory()->create();
    $role = UserRoleFactory::new()->create([
        'chat_activated' => true,
    ]);

    $listComponent = \Livewire\Livewire::test(ListChats::class)
        ->assertStatus(200);

    $listComponent->mountAction('create');
    FilamentCrudTestSupport::setFormData($listComponent, [
        'user_id' => $user->id,
        'user_role_id' => $role->id,
        'subject' => 'Feature chat',
        'subjectable_type' => null,
        'subjectable_id' => null,
    ], 'mountedActions.0.data');
    $listComponent
        ->callMountedAction()
        ->assertHasNoActionErrors();

    $chat = Chat::query()->where('subject', 'Feature chat')->first();

    expect($chat)->not->toBeNull();

    $editComponent = \Livewire\Livewire::test(EditChat::class, ['record' => $chat->getRouteKey()])
        ->assertStatus(200);

    FilamentCrudTestSupport::setFormData($editComponent, [
        'user_id' => $user->id,
        'user_role_id' => $role->id,
        'status' => ChatStatus::OPEN->value,
        'subject' => 'Feature chat updated',
        'subjectable_type' => null,
        'subjectable_id' => null,
    ]);

    $editComponent
        ->call('save')
        ->assertHasNoFormErrors();

    $chat = $chat->fresh();

    expect($chat?->subject)->toBe('Feature chat updated');
    expect($chat?->status)->toBe(ChatStatus::OPEN);

    \Livewire\Livewire::test(ListChats::class)
        ->assertStatus(200)
        ->callTableBulkAction(DeleteBulkAction::class, [$chat]);

    expect(Chat::query()->whereKey($chat->id)->exists())->toBeFalse();
});

function pageCrudCases(): array
{
    return [
        'user' => [
            'create_page' => CreateUser::class,
            'edit_page' => EditUser::class,
            'create_data' => fn () => [
                'name' => 'Feature User',
                'reg_id' => 991001,
            ],
            'record_lookup' => fn () => User::query()->where('reg_id', 991001)->first(),
            'edit_data' => fn (User $record) => [
                'name' => 'Feature User Updated',
                'reg_id' => $record->reg_id,
            ],
            'assert_updated' => function (?User $record): void {
                expect($record?->name)->toBe('Feature User Updated');
            },
            'assert_deleted' => fn () => expect(User::query()->where('reg_id', 991001)->exists())->toBeFalse(),
        ],
    ];
}

function actionCrudCases(): array
{
    return [
        'notification-route' => [
            'list_page' => ListNotificationRoutes::class,
            'create_data' => function () {
                $user = User::factory()->create();
                $notification = array_key_first(app(NotificationService::class)->getRoutableNotificationNames());

                expect($notification)->not->toBeNull();

                return [
                    'notification' => $notification,
                    'channels' => ['mail'],
                    'notifiable_type' => $user->getMorphClass(),
                    'notifiable_id' => $user->getKey(),
                ];
            },
            'record_lookup' => fn () => NotificationRoute::query()->first(),
            'edit_data' => function (NotificationRoute $record) {
                $role = UserRoleFactory::new()->create();

                return [
                    'channels' => ['mail', 'telegram'],
                    'notifiable_type' => $role->getMorphClass(),
                    'notifiable_id' => $role->getKey(),
                ];
            },
            'assert_updated' => function (?NotificationRoute $record): void {
                expect($record?->channels)->toBe(['mail', 'telegram']);
                expect($record?->notifiable_type)->toBe((new UserRole())->getMorphClass());
            },
            'assert_deleted' => fn () => expect(NotificationRoute::query()->count())->toBe(0),
        ],
        'page-hook' => [
            'list_page' => ManagePageHooks::class,
            'create_data' => fn () => [
                'id' => 'feature-hook',
                'content' => 'Feature hook content',
                'content_en' => 'Feature hook content en',
                'html' => false,
                'description' => 'Feature hook description',
            ],
            'record_lookup' => fn () => PageHook::query()->find('feature-hook'),
            'edit_data' => fn (PageHook $record) => [
                'id' => $record->id,
                'content' => 'Updated hook content',
                'content_en' => 'Updated hook content en',
                'html' => false,
                'description' => 'Updated hook description',
            ],
            'assert_updated' => function (?PageHook $record): void {
                expect($record?->content)->toBe('Updated hook content');
                expect($record?->description)->toBe('Updated hook description');
            },
            'assert_deleted' => fn () => expect(PageHook::query()->find('feature-hook'))->toBeNull(),
        ],
        'post-channel' => [
            'list_page' => ManagePostChannels::class,
            'create_data' => fn () => [
                'name' => 'Feature Channel',
                'channel_identifier' => 420001,
                'test_channel_identifier' => 420002,
                'language' => 'de',
                'default' => true,
                'info' => 'Internal channel info',
            ],
            'record_lookup' => fn () => PostChannel::query()->where('channel_identifier', 420001)->first(),
            'edit_data' => fn (PostChannel $record) => [
                'name' => 'Feature Channel Updated',
                'channel_identifier' => $record->channel_identifier,
                'test_channel_identifier' => 420099,
                'language' => 'en',
                'default' => false,
                'info' => 'Updated channel info',
            ],
            'assert_updated' => function (?PostChannel $record): void {
                expect($record?->name)->toBe('Feature Channel Updated');
                expect($record?->language)->toBe('en');
            },
            'assert_deleted' => fn () => expect(PostChannel::query()->where('channel_identifier', 420001)->exists())->toBeFalse(),
        ],
        'post' => [
            'list_page' => ManagePosts::class,
            'create_data' => fn () => [
                'text' => 'Feature post body',
                'text_en' => 'Feature post body en',
                'channels' => [],
            ],
            'record_lookup' => fn () => Post::query()->where('text', 'Feature post body')->first(),
            'edit_data' => fn (Post $record) => [
                'text' => 'Feature post body updated',
                'text_en' => $record->text_en,
            ],
            'assert_updated' => function (?Post $record): void {
                expect($record?->text)->toBe('Feature post body updated');
            },
            'assert_deleted' => fn () => expect(Post::query()->where('text', 'Feature post body updated')->exists())->toBeFalse(),
        ],
        'lost-found-item' => [
            'list_page' => ListLostFoundItems::class,
            'create_data' => fn () => [
                'lassie_id' => 880001,
                'image_url' => 'https://example.test/lost-found-image',
                'thumb_url' => 'https://example.test/lost-found-thumb',
                'title' => 'Feature Lost Item',
                'description' => 'Feature item description',
                'status' => 'F',
                'lost_at' => Carbon::parse('2026-03-28 10:00:00'),
                'found_at' => Carbon::parse('2026-03-29 12:00:00'),
                'returned_at' => null,
            ],
            'record_lookup' => fn () => LostFoundItem::query()->where('lassie_id', 880001)->first(),
            'edit_data' => fn (LostFoundItem $record) => [
                'lassie_id' => $record->lassie_id,
                'image_url' => $record->image_url,
                'thumb_url' => $record->thumb_url,
                'title' => 'Feature Lost Item Updated',
                'description' => 'Feature item description updated',
                'status' => 'F',
                'lost_at' => $record->lost_at,
                'found_at' => $record->found_at,
                'returned_at' => null,
            ],
            'delete_action' => null,
            'bulk_delete_action' => DeleteBulkAction::class,
            'assert_updated' => function (?LostFoundItem $record): void {
                expect($record?->title)->toBe('Feature Lost Item Updated');
                expect($record?->status)->toBe('F');
            },
            'assert_deleted' => fn () => expect(LostFoundItem::query()->where('lassie_id', 880001)->exists())->toBeFalse(),
        ],
    ];
}
