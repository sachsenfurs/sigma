<?php

use App\Enums\Approval;
use App\Filament\Resources\DepartmentInfos\Pages\ListDepartmentInfos;
use App\Filament\Resources\SigEvents\Pages\CreateSigEvent;
use App\Filament\Resources\SigEvents\Pages\EditSigEvent;
use App\Filament\Resources\SigForms\Pages\CreateSigForm;
use App\Filament\Resources\SigForms\Pages\EditSigForm;
use App\Filament\Resources\SigHosts\Pages\CreateSigHost;
use App\Filament\Resources\SigHosts\Pages\EditSigHost;
use App\Filament\Resources\SigTimeslots\Pages\EditSigTimeslot;
use App\Filament\Resources\SigTimeslots\Pages\ListSigTimeslots;
use App\Filament\Resources\TimetableEntries\Pages\CreateTimetableEntry;
use App\Filament\Resources\TimetableEntries\Pages\EditTimetableEntry;
use App\Models\DepartmentInfo;
use App\Models\SigEvent;
use App\Models\SigForm;
use App\Models\SigHost;
use App\Models\SigLocation;
use App\Models\SigTag;
use App\Models\TimetableEntry;
use App\Models\User;
use Database\Factories\SigTagFactory;
use Database\Factories\UserRoleFactory;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\Filament\FilamentCrudTestSupport;
use Tests\Support\Filament\FilamentTestSupport;

uses(RefreshDatabase::class);

it('completes CRUD cycles for SIG page-based resources', function () {
    FilamentTestSupport::actingAsAdmin($this);

    $failures = [];

    foreach (sigPageCrudCases() as $label => $case) {
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

it('completes SIG action-driven CRUD cycles for list resources', function () {
    FilamentTestSupport::actingAsAdmin($this);

    $failures = [];

    foreach (sigActionCrudCases() as $label => $case) {
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

it('creates sig timeslots from the list page and edits them through the top-level resource pages', function () {
    FilamentTestSupport::actingAsAdmin($this);

    $event = SigEvent::factory()->create([
        'private_group_ids' => null,
    ]);
    $location = SigLocation::factory()->create();
    $entry = TimetableEntry::factory()->create([
        'sig_event_id' => $event->id,
        'sig_location_id' => $location->id,
        'start' => now()->addDays(3)->setTime(13, 0),
        'end' => now()->addDays(3)->setTime(14, 30),
    ]);

    $createComponent = \Livewire\Livewire::test(ListSigTimeslots::class)
        ->assertStatus(200);

    $createComponent->mountAction('create');

    FilamentCrudTestSupport::setFormData($createComponent, [
        'timetable_entry_id' => $entry->id,
        'slot_start' => $entry->start->copy()->format('Y-m-d H:i:s'),
        'slot_end' => $entry->start->copy()->addMinutes(30)->format('Y-m-d H:i:s'),
        'reg_start' => now()->addDays(1)->setTime(10, 0)->format('Y-m-d H:i:s'),
        'reg_end' => now()->addDays(3)->setTime(12, 45)->format('Y-m-d H:i:s'),
        'max_users' => 8,
        'self_register' => true,
        'group_registration' => false,
    ], 'mountedActions.0.data');

    $createComponent
        ->callMountedAction()
        ->assertHasNoActionErrors();

    $timeslot = \App\Models\SigTimeslot::query()->latest('id')->first();

    expect($timeslot)->not->toBeNull();

    $editComponent = \Livewire\Livewire::test(EditSigTimeslot::class, ['record' => $timeslot->getRouteKey()])
        ->assertStatus(200);

    FilamentCrudTestSupport::setFormData($editComponent, [
        'timetable_entry_id' => $entry->id,
        'slot_start' => $entry->start->copy()->addMinutes(15)->format('Y-m-d H:i:s'),
        'slot_end' => $entry->start->copy()->addMinutes(45)->format('Y-m-d H:i:s'),
        'reg_start' => now()->addDays(1)->setTime(9, 0)->format('Y-m-d H:i:s'),
        'reg_end' => now()->addDays(3)->setTime(12, 30)->format('Y-m-d H:i:s'),
        'max_users' => 10,
        'self_register' => false,
        'group_registration' => true,
    ]);

    $editComponent
        ->call('save')
        ->assertHasNoFormErrors();

    $timeslot = $timeslot->fresh();

    expect($timeslot?->max_users)->toBe(10);
    expect((bool) $timeslot?->self_register)->toBeFalse();
    expect((bool) $timeslot?->group_registration)->toBeTrue();

    \Livewire\Livewire::test(ListSigTimeslots::class)
        ->assertStatus(200)
        ->callTableBulkAction(DeleteBulkAction::class, [$timeslot]);

    expect(\App\Models\SigTimeslot::query()->count())->toBe(0);
});

function sigPageCrudCases(): array
{
    return [
        'sig-host' => [
            'create_page' => CreateSigHost::class,
            'edit_page' => EditSigHost::class,
            'create_data' => fn () => [
                'name' => 'Feature Host',
                'reg_id' => 120001,
                'description' => 'Host description',
                'description_en' => 'Host description en',
                'hide' => false,
                'color' => '#cccccc',
            ],
            'record_lookup' => fn () => SigHost::query()->where('reg_id', 120001)->first(),
            'edit_data' => fn (SigHost $record) => [
                'name' => 'Feature Host Updated',
                'reg_id' => $record->reg_id,
                'description' => 'Host description updated',
                'description_en' => 'Host description en updated',
                'hide' => true,
                'color' => '#ababab',
            ],
            'assert_updated' => function (?SigHost $record): void {
                expect($record?->name)->toBe('Feature Host Updated');
                expect((bool) $record?->hide)->toBeTrue();
            },
            'assert_deleted' => fn () => expect(SigHost::query()->where('reg_id', 120001)->exists())->toBeFalse(),
        ],
        'sig-event' => [
            'create_page' => CreateSigEvent::class,
            'edit_page' => EditSigEvent::class,
            'create_data' => function () {
                $hostUser = User::factory()->create(['reg_id' => 555001]);
                $host = SigHost::factory()->create([
                    'reg_id' => $hostUser->reg_id,
                ]);
                $tag = SigTagFactory::new()->create();

                return [
                    'name' => 'Feature SIG',
                    'name_en' => 'Feature SIG EN',
                    'duration' => 60,
                    'sigHosts' => [$host->id],
                    'approval' => Approval::PENDING->value,
                    'languages' => ['de', 'en'],
                    'sigTags' => [$tag->id],
                    'reg_possible' => false,
                    'no_text' => false,
                    'description' => 'Feature description',
                    'description_en' => 'Feature description en',
                    'text_confirmed' => false,
                    'additional_info' => 'Feature additional info',
                    'attributes' => [],
                    'private_group_ids' => [],
                ];
            },
            'record_lookup' => fn () => SigEvent::query()->where('name', 'Feature SIG')->first(),
            'edit_data' => fn (SigEvent $record) => [
                'name' => 'Feature SIG Updated',
                'name_en' => 'Feature SIG EN Updated',
                'duration' => 90,
                'sigHosts' => $record->sigHosts->pluck('id')->all(),
                'approval' => Approval::APPROVED->value,
                'languages' => ['en'],
                'sigTags' => $record->sigTags->pluck('id')->all(),
                'reg_possible' => false,
                'no_text' => false,
                'description' => 'Feature description updated',
                'description_en' => 'Feature description en updated',
                'text_confirmed' => true,
                'additional_info' => 'Feature additional info updated',
                'attributes' => ['source' => 'feature-test'],
                'private_group_ids' => [],
            ],
            'assert_updated' => function (?SigEvent $record): void {
                expect($record?->name)->toBe('Feature SIG Updated');
                expect($record?->approval)->toBe(Approval::APPROVED);
                expect($record?->attributes)->toBe(['source' => 'feature-test']);
            },
            'assert_deleted' => fn () => expect(SigEvent::query()->where('name', 'Feature SIG Updated')->exists())->toBeFalse(),
        ],
        'sig-form' => [
            'create_page' => CreateSigForm::class,
            'edit_page' => EditSigForm::class,
            'create_data' => function () {
                $role = UserRoleFactory::new()->create();
                $event = SigEvent::factory()->create();

                return [
                    'name' => 'Feature Form',
                    'name_en' => 'Feature Form EN',
                    'slug' => 'feature-form',
                    'userRoles' => [$role->id],
                    'form_closed' => false,
                    'sig_events' => [$event->id],
                    'form_definition' => [[
                        'type' => 'text',
                        'data' => [
                            'label' => 'Feature field',
                            'label_en' => 'Feature field en',
                            'name' => 'feature_field',
                            'help_text' => 'Fill this field',
                            'help_text_en' => 'Fill this field en',
                            'required' => true,
                        ],
                    ]],
                ];
            },
            'record_lookup' => fn () => SigForm::withoutGlobalScopes()->where('slug', 'feature-form')->first(),
            'record_param' => fn (SigForm $record) => $record->slug,
            'edit_data' => fn (SigForm $record) => [
                'name' => 'Feature Form Updated',
                'name_en' => 'Feature Form EN Updated',
                'slug' => $record->slug,
                'userRoles' => $record->userRoles->pluck('id')->all(),
                'form_closed' => true,
                'sig_events' => $record->sigEvents->pluck('id')->all(),
                'form_definition' => [[
                    'type' => 'text',
                    'data' => [
                        'label' => 'Feature field updated',
                        'label_en' => 'Feature field en updated',
                        'name' => 'feature_field',
                        'help_text' => 'Updated help text',
                        'help_text_en' => 'Updated help text en',
                        'required' => false,
                    ],
                ]],
            ],
            'assert_updated' => function (?SigForm $record): void {
                expect($record?->name)->toBe('Feature Form Updated');
                expect((bool) $record?->form_closed)->toBeTrue();
                expect($record?->form_definition[0]['data']['label'] ?? null)->toBe('Feature field updated');
            },
            'assert_deleted' => fn () => expect(SigForm::withoutGlobalScopes()->where('slug', 'feature-form')->exists())->toBeFalse(),
        ],
        'timetable-entry' => [
            'create_page' => CreateTimetableEntry::class,
            'edit_page' => EditTimetableEntry::class,
            'create_data' => function () {
                $event = SigEvent::factory()->create([
                    'private_group_ids' => null,
                ]);
                $location = SigLocation::factory()->create();
                $start = now()->addDays(2)->setTime(12, 0);

                return [
                    'sig_event_id' => $event->id,
                    'sig_location_id' => $location->id,
                    'entries' => [[
                        'start' => $start->format('Y-m-d H:i:s'),
                        'end' => $start->copy()->addHour()->format('Y-m-d H:i:s'),
                    ]],
                    'new' => false,
                    'hide' => false,
                ];
            },
            'record_lookup' => fn () => TimetableEntry::query()->latest('id')->first(),
            'edit_data' => fn (TimetableEntry $record) => [
                'sig_event_id' => $record->sig_event_id,
                'sig_location_id' => $record->sig_location_id,
                'start' => $record->start->copy()->addHour()->format('Y-m-d H:i:s'),
                'end' => $record->end->copy()->addHour()->format('Y-m-d H:i:s'),
                'new' => true,
                'hide' => true,
                'cancelled' => false,
                'send_update' => false,
                'approval' => false,
                'comment' => 'Feature comment updated',
            ],
            'assert_updated' => function (?TimetableEntry $record): void {
                expect((bool) $record?->new)->toBeTrue();
                expect((bool) $record?->hide)->toBeTrue();
                expect($record?->comment)->toBe('Feature comment updated');
            },
            'assert_deleted' => fn () => expect(TimetableEntry::query()->count())->toBe(0),
        ],
    ];
}

function sigActionCrudCases(): array
{
    return [
        'department-info' => [
            'list_page' => ListDepartmentInfos::class,
            'create_data' => function () {
                $event = SigEvent::factory()->create([
                    'private_group_ids' => null,
                ]);
                $location = SigLocation::factory()->create();
                TimetableEntry::factory()->create([
                    'sig_event_id' => $event->id,
                    'sig_location_id' => $location->id,
                    'start' => now()->addDays(2)->setTime(10, 0),
                    'end' => now()->addDays(2)->setTime(11, 30),
                ]);
                $role = UserRoleFactory::new()->create();

                return [
                    'sig_event_id' => $event->id,
                    'user_role_id' => $role->id,
                    'additional_info' => 'Feature department info',
                ];
            },
            'record_lookup' => fn () => DepartmentInfo::query()->where('additional_info', 'Feature department info')->first(),
            'edit_data' => fn (DepartmentInfo $record) => [
                'sig_event_id' => $record->sig_event_id,
                'user_role_id' => $record->user_role_id,
                'additional_info' => 'Feature department info updated',
            ],
            'assert_updated' => function (?DepartmentInfo $record): void {
                expect($record?->additional_info)->toBe('Feature department info updated');
            },
            'assert_deleted' => fn () => expect(DepartmentInfo::query()->where('additional_info', 'Feature department info updated')->exists())->toBeFalse(),
        ],
    ];
}
