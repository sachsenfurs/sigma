<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ManageRecords;
use Tests\Support\Filament\FilamentTestSupport;

uses(RefreshDatabase::class);

it('mounts every non-shift filament resource page', function () {
    FilamentTestSupport::actingAsAdmin($this);
    $failures = [];

    foreach (FilamentTestSupport::resourcePages() as [$pageClass, $params, $record, $expectsAccess]) {
        try {
            $component = \Livewire\Livewire::test($pageClass, $params);

            if (! $expectsAccess) {
                $component->assertForbidden();
                continue;
            }

            $component->assertStatus(200);

            if (($record !== null) && is_subclass_of($pageClass, EditRecord::class)) {
                $component->assertFormExists();
            }

            if (is_subclass_of($pageClass, CreateRecord::class)) {
                $component->assertFormExists();
            }
        } catch (Throwable $throwable) {
            $failures[] = $pageClass . ': ' . $throwable->getMessage();
        }
    }

    expect($failures)->toBeEmpty(implode(PHP_EOL, $failures));
});
