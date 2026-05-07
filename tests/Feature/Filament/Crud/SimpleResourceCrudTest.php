<?php

use App\Filament\Resources\Ddas\ArtshowArtists\Pages\CreateArtshowArtist;
use App\Filament\Resources\Ddas\ArtshowArtists\Pages\EditArtshowArtist;
use App\Filament\Resources\Ddas\DealerTags\Pages\CreateDealerTag;
use App\Filament\Resources\Ddas\DealerTags\Pages\EditDealerTag;
use App\Filament\Resources\SigLocations\Pages\CreateSigLocation;
use App\Filament\Resources\SigLocations\Pages\EditSigLocation;
use App\Filament\Resources\SigTags\Pages\CreateSigTag;
use App\Filament\Resources\SigTags\Pages\EditSigTag;
use App\Filament\Resources\Socials\Pages\CreateSocial;
use App\Filament\Resources\Socials\Pages\EditSocial;
use App\Filament\Resources\UserRoles\Pages\CreateUserRole;
use App\Filament\Resources\UserRoles\Pages\EditUserRole;
use App\Models\Ddas\ArtshowArtist;
use App\Models\Ddas\DealerTag;
use App\Models\Info\Enums\ShowMode;
use App\Models\Info\Social;
use App\Models\SigLocation;
use App\Models\SigTag;
use App\Models\User;
use App\Models\UserRole;
use Filament\Actions\DeleteAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\Filament\FilamentCrudTestSupport;
use Tests\Support\Filament\FilamentTestSupport;

uses(RefreshDatabase::class);

it('completes CRUD cycles for simple filament resources', function () {
    FilamentTestSupport::actingAsAdmin($this);
    $failures = [];

    foreach (simpleCrudCases() as $label => $case) {
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

function simpleCrudCases(): array
{
    return [
        'sig-tag' => [
            'create_page' => CreateSigTag::class,
            'edit_page' => EditSigTag::class,
            'create_data' => fn () => [
                'name' => 'feature-tag',
                'description' => 'Feature tag description',
                'description_en' => 'Feature tag description en',
                'icon' => 'bi-lightning',
            ],
            'record_lookup' => fn () => SigTag::query()->where('name', 'feature-tag')->first(),
            'edit_data' => fn (SigTag $record) => [
                'name' => $record->name,
                'description' => 'Updated feature tag description',
                'description_en' => 'Updated feature tag description en',
                'icon' => 'bi-stars',
            ],
            'assert_updated' => function (?SigTag $record): void {
                expect($record?->description)->toBe('Updated feature tag description');
                expect($record?->icon)->toBe('bi-stars');
            },
            'assert_deleted' => fn () => expect(SigTag::query()->where('name', 'feature-tag')->exists())->toBeFalse(),
        ],
        'sig-location' => [
            'create_page' => CreateSigLocation::class,
            'edit_page' => EditSigLocation::class,
            'create_data' => fn () => [
                'name' => 'Feature Hall',
                'name_en' => 'Feature Hall EN',
                'description' => 'Main hall',
                'description_en' => 'Main hall EN',
                'render_ids' => ['hall-a', 'hall-b'],
                'floor' => '1',
                'room' => 'A101',
                'roomsize' => 'large',
                'seats' => '120',
                'infodisplay' => true,
                'essential' => false,
                'essential_description' => null,
                'essential_description_en' => null,
            ],
            'record_lookup' => fn () => SigLocation::query()->where('name', 'Feature Hall')->first(),
            'edit_data' => fn (SigLocation $record) => [
                'name' => $record->name,
                'name_en' => 'Feature Hall Updated',
                'description' => $record->description,
                'description_en' => $record->description_en,
                'render_ids' => ['hall-c'],
                'floor' => '2',
                'room' => 'B202',
                'roomsize' => 'medium',
                'seats' => '80',
                'infodisplay' => false,
                'essential' => true,
                'essential_description' => 'Important location',
                'essential_description_en' => 'Important location EN',
            ],
            'assert_updated' => function (?SigLocation $record): void {
                expect($record?->name_en)->toBe('Feature Hall Updated');
                expect($record?->essential)->toBeTrue();
                expect($record?->render_ids)->toBe(['hall-c']);
            },
            'assert_deleted' => fn () => expect(SigLocation::query()->where('name', 'Feature Hall')->exists())->toBeFalse(),
        ],
        'user-role' => [
            'create_page' => CreateUserRole::class,
            'edit_page' => EditUserRole::class,
            'create_data' => fn () => [
                'name' => 'Feature Role',
                'name_en' => 'Feature Role EN',
                'registration_system_key' => 'feature-role',
                'chat_activated' => true,
                'fore_color' => '#101010',
                'border_color' => '#202020',
                'background_color' => '#EFEFEF',
                'permissions' => [],
            ],
            'record_lookup' => fn () => UserRole::query()->where('name', 'Feature Role')->first(),
            'edit_data' => fn (UserRole $record) => [
                'name' => $record->name,
                'name_en' => 'Feature Role Updated',
                'registration_system_key' => 'feature-role-updated',
                'chat_activated' => false,
                'fore_color' => '#303030',
                'border_color' => '#404040',
                'background_color' => '#DDDDDD',
                'permissions' => [],
            ],
            'assert_updated' => function (?UserRole $record): void {
                expect($record?->name_en)->toBe('Feature Role Updated');
                expect((bool) $record?->chat_activated)->toBeFalse();
            },
            'assert_deleted' => fn () => expect(UserRole::query()->where('name', 'Feature Role')->exists())->toBeFalse(),
        ],
        'dealer-tag' => [
            'create_page' => CreateDealerTag::class,
            'edit_page' => EditDealerTag::class,
            'create_data' => fn () => [
                'name' => 'Feature Dealer Tag',
                'name_en' => 'Feature Dealer Tag EN',
            ],
            'record_lookup' => fn () => DealerTag::query()->where('name', 'Feature Dealer Tag')->first(),
            'edit_data' => fn (DealerTag $record) => [
                'name' => $record->name,
                'name_en' => 'Feature Dealer Tag Updated',
            ],
            'assert_updated' => function (?DealerTag $record): void {
                expect($record?->name_en)->toBe('Feature Dealer Tag Updated');
            },
            'assert_deleted' => fn () => expect(DealerTag::query()->where('name', 'Feature Dealer Tag')->exists())->toBeFalse(),
        ],
        'artshow-artist' => [
            'create_page' => CreateArtshowArtist::class,
            'edit_page' => EditArtshowArtist::class,
            'create_data' => function () {
                $user = User::factory()->create();

                return [
                    'user_id' => $user->id,
                    'name' => 'Feature Artist',
                    'social' => 'https://example.test/artist',
                ];
            },
            'record_lookup' => fn () => ArtshowArtist::query()->where('name', 'Feature Artist')->first(),
            'edit_data' => fn (ArtshowArtist $record) => [
                'user_id' => $record->user_id,
                'name' => $record->name,
                'social' => 'https://example.test/artist-updated',
            ],
            'assert_updated' => function (?ArtshowArtist $record): void {
                expect($record?->social)->toBe('https://example.test/artist-updated');
            },
            'assert_deleted' => fn () => expect(ArtshowArtist::query()->where('name', 'Feature Artist')->exists())->toBeFalse(),
        ],
        'social' => [
            'create_page' => CreateSocial::class,
            'edit_page' => EditSocial::class,
            'create_data' => fn () => [
                'description' => 'Feature Social',
                'description_en' => 'Feature Social EN',
                'link_name' => 'Feature Link',
                'link' => 'https://example.test/social',
                'link_name_en' => 'Feature Link EN',
                'link_en' => 'https://example.test/social-en',
                'icon' => 'bi-globe',
                'image' => null,
                'image_en' => null,
                'order' => 5,
                'show_on' => [ShowMode::SIGNAGE->value, ShowMode::FOOTER_TEXT->value],
            ],
            'record_lookup' => fn () => Social::query()->where('description', 'Feature Social')->first(),
            'edit_data' => fn (Social $record) => [
                'description' => $record->description,
                'description_en' => 'Feature Social Updated',
                'link_name' => $record->link_name,
                'link' => 'https://example.test/social-updated',
                'link_name_en' => $record->link_name_en,
                'link_en' => $record->link_en,
                'icon' => 'bi-stars',
                'image' => null,
                'image_en' => null,
                'order' => 9,
                'show_on' => [ShowMode::FOOTER_ICON->value],
            ],
            'assert_updated' => function (?Social $record): void {
                expect($record?->description_en)->toBe('Feature Social Updated');
                expect($record?->show_on)->toBe([ShowMode::FOOTER_ICON]);
            },
            'assert_deleted' => fn () => expect(Social::query()->where('description', 'Feature Social')->exists())->toBeFalse(),
        ],
    ];
}
