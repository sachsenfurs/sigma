<?php

namespace App\Filament\Resources\SigFormsResource\Pages;

use App\Filament\Resources\SigFormsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSigForms extends EditRecord
{
    protected static string $resource = SigFormsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('open_form')
                ->label(__('Open form'))
                ->translateLabel()
                ->url(fn ($record) => route('forms.show', [ 'form' => $record->slug ] ), true),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            SigFormsResource\Widgets\FilledForms::class
        ];
    }
}
