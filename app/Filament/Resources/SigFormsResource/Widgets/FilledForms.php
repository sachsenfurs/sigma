<?php

namespace App\Filament\Resources\SigFormsResource\Widgets;

use App\Models\SigFilledForms;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class FilledForms extends BaseWidget
{
    public ?Model $record = null;

    protected int | string | array $columnSpan = 'full';

    protected function getTableHeading(): string|null|Htmlable
    {
        return __('Filled forms');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                SigFilledForms::query()
                    ->where('sig_forms_id', $this->record->id)
            )
            ->columns($this->getTableColumns())
            ->actions($this->getTableEntryActions())
            ->striped();
    }

    protected function getTableColumns(): array
    {
        $tableColumns = [];
        foreach ($this->record->form_definition as $formDefinition) {
            $formData = $formDefinition['data'];
            switch ($formDefinition['type']) {
                case 'text':
                    $tableColumns[] = Tables\Columns\TextColumn::make($formData['name'])
                        ->label(App::getLocale() === 'en' ? $formData['label_en'] : $formData['label'] . ($formData['required'] ? ' *' : ''))
                        ->getStateUsing(function ($record) use ($formData) {
                            return $record->form_data['form_data'][$formData['name']] ?? '';
                        });
                    break;
                case 'textarea':
                    $tableColumns[] = Tables\Columns\TextColumn::make($formData['name'])
                        ->label(App::getLocale() === 'en' ? $formData['label_en'] : $formData['label'] . ($formData['required'] ? ' *' : ''))
                        ->getStateUsing(function ($record) use ($formData) {
                            return $record->form_data['form_data'][$formData['name']] ?? '';
                        })
                        ->limit(50);
                    break;
                case 'file_upload':
                    $tableColumns[] = Tables\Columns\ImageColumn::make($formData['name'])
                        ->label(App::getLocale() === 'en' ? $formData['label_en'] : $formData['label'] . ($formData['required'] ? ' *' : ''))
                        ->getStateUsing(function ($record) use ($formData) {
                            return $record->form_data['form_data'][$formData['name']] ?? '';
                        })
                        ->url(function ($record) use ($formData) {
                            if ($record->form_data['form_data'][$formData['name']] ?? null) {
                                return Storage::url($record->form_data['form_data'][$formData['name']] ?? null);
                            }
                            return null;
                        }, true);
                    break;
                case 'checkbox':
                    $tableColumns[] = Tables\Columns\CheckboxColumn::make($formData['name'])
                        ->label(App::getLocale() === 'en' ? $formData['label_en'] : $formData['label'] . ($formData['required'] ? ' *' : ''))
                        ->getStateUsing(function ($record) use ($formData) {
                            return $record->form_data['form_data'][$formData['name']] ?? '';
                        });
                    break;
                case 'select':
                    $tableColumns[] = Tables\Columns\TextColumn::make($formData['name'])
                        ->label(App::getLocale() === 'en' ? $formData['label_en'] : $formData['label'] . ($formData['required'] ? ' *' : ''))
                        ->getStateUsing(function ($record) use ($formData) {
                            $options = $formData['options'];
                            foreach ($options as $option) {
                                if ($option['data']['value'] === $record->form_data['form_data'][$formData['name']]) {
                                    return App::getLocale() === 'en' ? $option['data']['label_en'] : $option['data']['label'];
                                }
                            }
                            return $record->form_data['form_data'][$formData['name']] ?? '';
                        });
                    break;
            }
        }
        return $tableColumns;
    }

    protected function getTableEntryActions(): array
    {
        return [
            DeleteAction::make('delete')
                ->label('Delete')
                ->translateLabel()
                ->modalHeading(__('Really delete form data?')),
        ];
    }
}
