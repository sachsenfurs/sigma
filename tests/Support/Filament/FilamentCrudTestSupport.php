<?php

namespace Tests\Support\Filament;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Livewire\Livewire;

class FilamentCrudTestSupport
{
    public static function setFormData(mixed $component, array $data, string $statePath = 'data'): void
    {
        foreach ($data as $key => $value) {
            $component->set("{$statePath}.{$key}", $value);
        }
    }

    public static function runPageCrudCycle(array $case): void
    {
        $createComponent = Livewire::test($case['create_page'])
            ->assertStatus(200);

        self::setFormData($createComponent, $case['create_data'](), $case['create_state_path'] ?? 'data');

        $createComponent
            ->call($case['create_method'] ?? 'create')
            ->assertHasNoFormErrors();

        $record = $case['record_lookup']();

        expect($record)->not->toBeNull("Create lookup returned null for {$case['label']}");

        $editComponent = Livewire::test($case['edit_page'], [
            'record' => ($case['record_param'] ?? fn ($record) => $record->getRouteKey())($record),
        ])->assertStatus(200);

        self::setFormData($editComponent, $case['edit_data']($record), $case['edit_state_path'] ?? 'data');

        $editComponent
            ->call($case['save_method'] ?? 'save')
            ->assertHasNoFormErrors();

        $case['assert_updated']($record->fresh());

        $deleteAction = array_key_exists('delete_action', $case) ? $case['delete_action'] : DeleteAction::class;

        if ($deleteAction !== null) {
            $editComponent->callAction($deleteAction);
            $case['assert_deleted']();
        }
    }

    public static function runListActionCrudCycle(array $case): void
    {
        $listComponent = Livewire::test($case['list_page'])
            ->assertStatus(200);

        $listComponent->mountAction($case['create_action'] ?? 'create');
        self::setFormData($listComponent, $case['create_data'](), 'mountedActions.0.data');
        $listComponent
            ->callMountedAction()
            ->assertHasNoActionErrors();

        $record = $case['record_lookup']();

        expect($record)->not->toBeNull("Create lookup returned null for {$case['label']}");

        $listComponent = Livewire::test($case['list_page'])
            ->assertStatus(200)
            ->assertCanSeeTableRecords([$record]);

        $editAction = array_key_exists('edit_action', $case) ? $case['edit_action'] : EditAction::class;

        if ($editAction !== null) {
            $listComponent->mountTableAction($editAction, $record);
            self::setFormData($listComponent, $case['edit_data']($record), 'mountedActions.0.data');
            $listComponent
                ->callMountedTableAction()
                ->assertHasNoTableActionErrors();

            $case['assert_updated']($record->fresh());
        }

        $deleteAction = array_key_exists('delete_action', $case) ? $case['delete_action'] : DeleteAction::class;

        if ($deleteAction !== null) {
            $listComponent->callTableAction($deleteAction, $record);
            $case['assert_deleted']();
            return;
        }

        $bulkDeleteAction = $case['bulk_delete_action'] ?? null;

        if ($bulkDeleteAction !== null) {
            $listComponent->callTableBulkAction($bulkDeleteAction, [$record]);
            $case['assert_deleted']();
        }
    }

    public static function runEditOnlyCycle(array $case): void
    {
        $record = $case['record_factory']();

        $editComponent = Livewire::test($case['edit_page'], [
            'record' => ($case['record_param'] ?? fn ($record) => $record->getRouteKey())($record),
        ])->assertStatus(200);

        self::setFormData($editComponent, $case['edit_data']($record), $case['edit_state_path'] ?? 'data');

        $editComponent
            ->call($case['save_method'] ?? 'save')
            ->assertHasNoFormErrors();

        $case['assert_updated']($record->fresh());

        $deleteAction = $case['delete_action'] ?? null;

        if ($deleteAction !== null) {
            $editComponent->callAction($deleteAction);
            $case['assert_deleted']();
        }
    }
}
