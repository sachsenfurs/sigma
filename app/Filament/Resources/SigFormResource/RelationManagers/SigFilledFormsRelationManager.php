<?php

namespace App\Filament\Resources\SigFormResource\RelationManagers;

use App\Enums\Approval;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\App;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Get;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
class SigFilledFormsRelationManager extends RelationManager
{
    protected static string $relationship = 'sigFilledForms';
    protected int | string | array $columnSpan = 'full';

    protected function getTableHeading(): string|null|Htmlable {
        return __('Filled forms');
    }

    public function form(Form $form): Form {
        return $form
            ->schema([
                Forms\Components\TextInput::make('sig_form_id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table {
        return $table
            ->defaultSort("approval", "ASC")
            ->recordTitleAttribute('sig_form_id')
            ->filters([
                self::getApprovalFilter(),
            ])
            ->bulkActions([
                Approval::getBulkAction([self::getRejectComponent()]),
            ])
            ->columns($this->getTableColumns())
            ->actions($this->getTableEntryActions())
            ->striped();
    }

    protected function getTableColumns(): array {
        $tableColumns = [
            Tables\Columns\IconColumn::make('approval')
                ->label('Approval')
                ->translateLabel(),
            Tables\Columns\TextColumn::make('rejection_reason'),
            Tables\Columns\TextColumn::make("user.name"),
            Tables\Columns\TextColumn::make('updated_at')
             ->since(),
        ];
        foreach ($this->ownerRecord->form_definition as $formDefinition) {
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
                        ->listWithLineBreaks(true)
                        ->formatStateUsing(fn($state) => new HtmlString(nl2br(htmlspecialchars($state))));
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
                    $tableColumns[] = Tables\Columns\IconColumn::make($formData['name'])
                        ->boolean()
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
                                $data = $option['data'] ?? [];
//                                dd($option);
//                                dd($formData['name'] ,$record->form_data['form_data'][$formData['name']]);
                                if (($data['value'] ?? null) === ($record->form_data['form_data'][$formData['name']] ?? null)) {
                                    return App::getLocale() === 'en' ? $data['label_en'] : $data['label'];
                                }
                            }
                            return $record->form_data['form_data'][$formData['name']] ?? '';
                        });
                    break;
            }
        }
        return $tableColumns;
    }

    protected function getTableEntryActions(): array {
        return [
            Tables\Actions\EditAction::make()
                ->authorize("update")
                ->modalHeading(__('Approve or reject form'))
                ->modalDescription(function ($record) {
                    if (($record->approval === Approval::REJECTED) && ($record->rejection_reason)) {
                        $reasonText = __('This form was rejected during the last audit for the following reason: ');
                        return new HtmlString('<div style="color: red;">' . $reasonText . $record->rejection_reason . '</div>');
                    }
                    return null;
                })
                ->form([
                    Forms\Components\Radio::make('approval')
                              ->label('Approval')
                              ->translateLabel()
                              ->required()
                              ->options(Approval::class)
                              ->live(),
                    self::getRejectComponent()
                ]),

            DeleteAction::make('delete')
                ->label('Delete')
                ->translateLabel()
                ->modalHeading(__('Really delete form data?')),
        ];
    }

    private static function getRejectComponent(): Forms\Components\Component {
        return Textarea::make('rejection_reason')
                ->label('Rejection reason')
                ->translateLabel()
                ->visible(fn (Get $get) => $get('approval') == Approval::REJECTED->value)
                ->required(fn (Get $get) => $get('approval') == Approval::REJECTED->value)
                ->live()
                ->rows(3);
    }
    private static function getApprovalFilter(): Tables\Filters\SelectFilter {
        return Tables\Filters\SelectFilter::make('approval')
            ->label('Approval')
            ->translateLabel()
            ->options(Approval::class);
    }
}
