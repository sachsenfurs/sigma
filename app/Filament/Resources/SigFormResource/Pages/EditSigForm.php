<?php

namespace App\Filament\Resources\SigFormResource\Pages;

use App\Filament\Resources\SigFormResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSigForm extends EditRecord
{
    protected static string $resource = SigFormResource::class;

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
            SigFormResource\Widgets\FilledForms::class
        ];
    }
}
