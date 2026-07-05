<?php

namespace App\Filament\Resources\SigFormResource\Pages;

use App\Filament\Resources\SigFormResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\URL;

class EditSigForm extends EditRecord
{
    protected static string $resource = SigFormResource::class;

    protected function getHeaderActions(): array {
        return [
            Actions\Action::make('open_form')
                          ->label(__('Open form'))
                          ->translateLabel()
                          ->url(fn ($record) => route('forms.show', [ 'form' => $record->slug ] ), true),
            Actions\DeleteAction::make(),
            Actions\ActionGroup::make([
                Actions\Action::make('open_api')
                    ->label(__('Open API'))
                    ->translateLabel()
                    ->url(fn ($record) => URL::signedRoute("api.forms", $this->record->slug ?: ""), true),
            ]),
        ];
    }

}
