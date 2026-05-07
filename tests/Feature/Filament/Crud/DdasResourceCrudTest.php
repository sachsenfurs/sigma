<?php

use App\Enums\Approval;
use App\Enums\Rating;
use App\Filament\Resources\Ddas\ArtshowPickups\Pages\CreateArtshowPickup;
use App\Filament\Resources\Ddas\ArtshowPickups\Pages\EditArtshowPickup;
use App\Filament\Resources\Ddas\ArtshowPickups\Pages\ListArtshowPickups;
use App\Filament\Resources\Ddas\ArtshowBids\Pages\ListArtshowBids;
use App\Filament\Resources\Ddas\ArtshowItems\Pages\EditArtshowItem;
use App\Filament\Resources\Ddas\Dealers\Pages\CreateDealer;
use App\Filament\Resources\Ddas\Dealers\Pages\EditDealer;
use App\Models\Ddas\ArtshowArtist;
use App\Models\Ddas\ArtshowBid;
use App\Models\Ddas\ArtshowItem;
use App\Models\Ddas\ArtshowPickup;
use App\Models\Ddas\Dealer;
use App\Models\Ddas\DealerTag;
use App\Models\SigLocation;
use App\Models\User;
use Database\Factories\Ddas\ArtshowPickupFactory;
use Database\Factories\Ddas\DealerTagFactory;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\Filament\FilamentCrudTestSupport;
use Tests\Support\Filament\FilamentTestSupport;

uses(RefreshDatabase::class);

it('completes DDAS CRUD cycles for create/edit resources', function () {
    FilamentTestSupport::actingAsAdmin($this);

    $failures = [];

    foreach (ddasPageCrudCases() as $label => $case) {
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

it('completes DDAS list-action CRUD cycles where resources use modal actions', function () {
    FilamentTestSupport::actingAsAdmin($this);

    $failures = [];

    foreach (ddasActionCrudCases() as $label => $case) {
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

it('updates and deletes edit-only DDAS resources', function () {
    FilamentTestSupport::actingAsAdmin($this);

    $failures = [];

    foreach (ddasEditOnlyCases() as $label => $case) {
        try {
            FilamentCrudTestSupport::runEditOnlyCycle($case);
        } catch (\Throwable $throwable) {
            $failures[] = $label . ': ' . $throwable->getMessage();
        }
    }

    expect($failures)->toBeEmpty(implode(PHP_EOL, $failures));
});

it('keeps artshow pickup resource pages inaccessible', function () {
    FilamentTestSupport::actingAsAdmin($this);

    $pickup = ArtshowPickupFactory::new()->create();

    \Livewire\Livewire::test(ListArtshowPickups::class)
        ->assertForbidden();

    \Livewire\Livewire::test(CreateArtshowPickup::class)
        ->assertForbidden();

    \Livewire\Livewire::test(EditArtshowPickup::class, ['record' => $pickup->getRouteKey()])
        ->assertForbidden();
});

function ddasPageCrudCases(): array
{
    return [
        'dealer' => [
            'create_page' => CreateDealer::class,
            'edit_page' => EditDealer::class,
            'create_data' => function () {
                $user = User::factory()->create();
                $location = SigLocation::factory()->create();
                $tag = DealerTagFactory::new()->create();

                return [
                    'user_id' => $user->id,
                    'approval' => Approval::PENDING->value,
                    'name' => 'Feature Dealer',
                    'gallery_link' => 'https://example.test/dealer',
                    'sig_location_id' => $location->id,
                    'info' => 'Dealer info',
                    'info_en' => 'Dealer info en',
                    'additional_info' => 'Dealer additional info',
                    'icon_file' => null,
                    'tags' => [$tag->id],
                ];
            },
            'record_lookup' => fn () => Dealer::query()->where('name', 'Feature Dealer')->first(),
            'edit_data' => function (Dealer $record) {
                return [
                    'user_id' => $record->user_id,
                    'approval' => Approval::APPROVED->value,
                    'name' => 'Feature Dealer Updated',
                    'gallery_link' => 'https://example.test/dealer-updated',
                    'sig_location_id' => $record->sig_location_id,
                    'info' => 'Dealer info updated',
                    'info_en' => 'Dealer info en updated',
                    'additional_info' => 'Dealer additional info updated',
                    'icon_file' => null,
                    'tags' => $record->tags->pluck('id')->all(),
                ];
            },
            'assert_updated' => function (?Dealer $record): void {
                expect($record?->name)->toBe('Feature Dealer Updated');
                expect($record?->approval)->toBe(Approval::APPROVED);
            },
            'assert_deleted' => fn () => expect(Dealer::query()->where('name', 'Feature Dealer Updated')->exists())->toBeFalse(),
        ],
    ];
}

function ddasActionCrudCases(): array
{
    return [
        'artshow-bid' => [
            'list_page' => ListArtshowBids::class,
            'create_data' => function () {
                $artistOwner = User::factory()->create();
                $bidder = User::factory()->create();
                $artist = ArtshowArtist::factory()->create([
                    'user_id' => $artistOwner->id,
                ]);
                $item = ArtshowItem::factory()->create([
                    'artshow_artist_id' => $artist->id,
                    'approval' => Approval::APPROVED->value,
                    'auction' => true,
                    'locked' => false,
                    'sold' => false,
                    'starting_bid' => 10,
                ]);

                return [
                    'artshow_item_id' => $item->id,
                    'user_id' => $bidder->id,
                    'value' => 15,
                ];
            },
            'record_lookup' => fn () => ArtshowBid::query()->where('value', 15)->first(),
            'edit_data' => function (ArtshowBid $record) {
                return [
                    'artshow_item_id' => $record->artshow_item_id,
                    'user_id' => $record->user_id,
                    'value' => 20,
                ];
            },
            'assert_updated' => function (?ArtshowBid $record): void {
                expect((int) $record?->value)->toBe(20);
            },
            'delete_action' => null,
            'bulk_delete_action' => DeleteBulkAction::class,
            'assert_deleted' => fn () => expect(ArtshowBid::query()->where('value', 20)->exists())->toBeFalse(),
        ],
    ];
}

function ddasEditOnlyCases(): array
{
    return [
        'artshow-item' => [
            'record_factory' => fn () => ArtshowItem::factory()->create([
                'approval' => Approval::PENDING->value,
                'auction' => true,
                'starting_bid' => 5,
                'charity_percentage' => 10,
                'image' => null,
            ]),
            'edit_page' => EditArtshowItem::class,
            'edit_data' => function (ArtshowItem $record) {
                return [
                    'name' => 'Feature Item Updated',
                    'approval' => Approval::APPROVED->value,
                    'description' => 'Updated item description',
                    'description_en' => 'Updated item description en',
                    'auction' => true,
                    'starting_bid' => 25,
                    'charity_percentage' => 35,
                    'additional_info' => 'Updated additional info',
                    'image' => null,
                    'rating' => Rating::NSFW->value,
                    'locked' => true,
                ];
            },
            'assert_updated' => function (?ArtshowItem $record): void {
                expect($record?->name)->toBe('Feature Item Updated');
                expect($record?->approval)->toBe(Approval::APPROVED);
                expect($record?->rating)->toBe(Rating::NSFW);
            },
            'delete_action' => DeleteAction::class,
            'assert_deleted' => fn () => expect(ArtshowItem::query()->where('name', 'Feature Item Updated')->exists())->toBeFalse(),
        ],
    ];
}
