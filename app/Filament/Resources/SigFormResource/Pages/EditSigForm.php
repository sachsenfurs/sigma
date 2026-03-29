<?php

namespace App\Filament\Resources\SigFormResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use App\Filament\Resources\SigFormResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSigForm extends EditRecord
{
    protected static string $resource = SigFormResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('open_form')
                ->label(__('Open form'))
                ->translateLabel()
                ->url(fn ($record) => route('forms.show', [ 'form' => $record->slug ] ), true),
            DeleteAction::make(),
        ];
    }

}
