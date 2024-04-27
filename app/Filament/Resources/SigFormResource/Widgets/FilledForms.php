<?php

namespace App\Filament\Resources\SigFormResource\Widgets;

use App\Models\SigFilledForm;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Get;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

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
                SigFilledForm::query()
                    ->where('sig_form_id', $this->record->id)
                    ->orderBy('approved')
            )
            ->filters([
                self::getApprovedFilter(),
            ])
            ->columns($this->getTableColumns())
            ->actions($this->getTableEntryActions())
            ->striped();
    }

    protected function getTableColumns(): array
    {
        $tableColumns = [
            Tables\Columns\IconColumn::make('approved')
                ->label('Approved')
                ->translateLabel()
                ->boolean()
                ->getStateUsing(function ($record) {
                    return match($record->approved) {
                        0 => null,
                        1 => true,
                        2 => false,
                    };
                }),
        ];
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
            EditAction::make('approval')
                ->label('Approval')
                ->translateLabel()
                ->modalHeading(__('Approve or reject form'))
                ->modalDescription(function ($record) {
                    if (($record->approved === 0) && ($record->rejection_reason)) {
                        $reasonText = __('This form was rejected during the last audit for the following reason: ');
                        return new HtmlString('<div style="color: red;">' . $reasonText . $record->rejection_reason . '</div>');
                    }
                    return null;
                })
                ->form([
                    Select::make('approved')
                        ->label('Approval')
                        ->translateLabel()
                        ->required()
                        ->live()
                        ->options([
                            1 => __('Approve'),
                            2 => __('Reject')
                        ]),
                    Textarea::make('rejection_reason')
                        ->label('Rejection reason')
                        ->translateLabel()
                        ->visible(fn (Get $get) => $get('approved') == 2)
                        ->required(fn (Get $get) => $get('approved') == 2)
                        ->live()
                        ->rows(3),
                ]),
            DeleteAction::make('delete')
                ->label('Delete')
                ->translateLabel()
                ->modalHeading(__('Really delete form data?')),
        ];
    }

    private static function getApprovedFilter(): Tables\Filters\SelectFilter
    {
        return Tables\Filters\SelectFilter::make('approved')
            ->label('Approval')
            ->translateLabel()
            ->options([
                '0' => __('To be approved'),
                '1' => __('Approved'),
                '2' => __('Rejected'),
            ]);
    }
}
