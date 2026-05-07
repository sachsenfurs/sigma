<?php

use App\Filament\Pages\Settings\AppSettingsPage;
use App\Filament\Pages\Settings\ArtShowSettingsPage;
use App\Filament\Pages\Settings\ChatSettingsPage;
use App\Filament\Pages\Settings\DealerSettingsPage;
use App\Filament\Pages\Settings\PageHookSettingsPage;
use App\Filament\Pages\SendMassMail;
use App\Filament\Pages\SigPlanner;
use App\Models\User;
use App\Settings\AppSettings;
use App\Settings\ArtShowSettings;
use App\Settings\ChatSettings;
use App\Settings\DealerSettings;
use App\Settings\PageHookSettings;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Support\Filament\FilamentCrudTestSupport;
use Tests\Support\Filament\FilamentTestSupport;

uses(RefreshDatabase::class);

it('saves all filament settings pages', function () {
    FilamentTestSupport::actingAsAdmin($this);

    $failures = [];

    foreach (settingsPageCases() as $label => $case) {
        try {
            $component = Livewire::test($case['page'])
                ->assertStatus(200);

            FilamentCrudTestSupport::setFormData($component, $case['data']());

            $component
                ->call('save')
                ->assertHasNoFormErrors();

            $case['assert']();
        } catch (\Throwable $throwable) {
            $failures[] = $label . ': ' . $throwable->getMessage();
        }
    }

    expect($failures)->toBeEmpty(implode(PHP_EOL, $failures));
});

it('restricts chat settings to super admins', function () {
    FilamentTestSupport::enableFeatureSettings();
    Filament::setCurrentPanel(Filament::getPanel('admin'));

    $this->actingAs(User::factory()->create());

    Livewire::test(ChatSettingsPage::class)
        ->assertForbidden();
});

it('restricts mass mail to chat admins', function () {
    FilamentTestSupport::enableFeatureSettings();
    Filament::setCurrentPanel(Filament::getPanel('admin'));

    $this->actingAs(User::factory()->create());

    Livewire::test(SendMassMail::class)
        ->assertForbidden();
});

it('renders the sig planner page for admins', function () {
    FilamentTestSupport::actingAsAdmin($this);

    Livewire::test(SigPlanner::class)
        ->assertStatus(200);
});

function settingsPageCases(): array
{
    return [
        'app-settings' => [
            'page' => AppSettingsPage::class,
            'data' => fn () => [
                'event_name' => 'Feature Con',
                'event_start' => '2026-06-01 10:00:00',
                'event_end' => '2026-06-03 18:00:00',
                'show_schedule_date' => '2026-05-20 09:00:00',
                'sig_application_deadline' => '2026-05-01 12:00:00',
                'short_domain' => 'feature.test/con/',
                'accept_sigs_after_deadline' => true,
                'paid_only' => false,
                'lassie_api_key' => 'lassie-key',
                'lassie_con_id' => 101,
                'lassie_con_event_id' => 202,
                'lost_found_enabled' => true,
                'telegram_bot_name' => 'feature_bot',
                'telegram_bot_token' => 'telegram-token',
                'deepl_api_key' => 'deepl-key',
                'deepl_source_lang' => 'DE',
                'deepl_target_lang' => 'EN',
            ],
            'assert' => function (): void {
                $settings = app(AppSettings::class);

                expect($settings->event_name)->toBe('Feature Con');
                expect($settings->short_domain)->toBe('feature.test/con/');
                expect($settings->telegram_bot_name)->toBe('feature_bot');
                expect($settings->deepl_target_lang)->toBe('EN');
            },
        ],
        'artshow-settings' => [
            'page' => ArtShowSettingsPage::class,
            'data' => fn () => [
                'enabled' => true,
                'item_deadline' => '2026-05-10 18:00:00',
                'show_items_date' => '2026-05-15 12:00:00',
                'charity_min_percentage' => 20,
                'paid_only' => true,
                'bid_start_date' => '2026-05-20 08:00:00',
                'bid_end_date' => '2026-06-02 20:00:00',
                'max_bids_per_item' => 7,
                'require_checkin' => true,
            ],
            'assert' => function (): void {
                $settings = app(ArtShowSettings::class);

                expect($settings->charity_min_percentage)->toBe(20);
                expect($settings->max_bids_per_item)->toBe(7);
                expect($settings->require_checkin)->toBeTrue();
            },
        ],
        'dealer-settings' => [
            'page' => DealerSettingsPage::class,
            'data' => fn () => [
                'enabled' => true,
                'signup_deadline' => '2026-05-12 18:00:00',
                'show_dealers_date' => '2026-05-20 11:00:00',
                'paid_only' => true,
                'image_mandatory' => true,
            ],
            'assert' => function (): void {
                $settings = app(DealerSettings::class);

                expect($settings->paid_only)->toBeTrue();
                expect($settings->image_mandatory)->toBeTrue();
            },
        ],
        'chat-settings' => [
            'page' => ChatSettingsPage::class,
            'data' => fn () => [
                'enabled' => false,
            ],
            'assert' => function (): void {
                $settings = app(ChatSettings::class);

                expect($settings->enabled)->toBeFalse();
            },
        ],
        'pagehook-settings' => [
            'page' => PageHookSettingsPage::class,
            'data' => fn () => [
                'show_in_source' => true,
            ],
            'assert' => function (): void {
                $settings = app(PageHookSettings::class);

                expect($settings->show_in_source)->toBeTrue();
            },
        ],
    ];
}
