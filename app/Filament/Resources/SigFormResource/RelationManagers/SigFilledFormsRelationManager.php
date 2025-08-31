<?php

namespace App\Filament\Resources\SigFormResource\RelationManagers;

use App\Enums\Approval;
use App\Filament\Helper\FormHelper;
use App\Models\SigFilledForm;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Colors\Color;
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
            ->headerActions([
                Tables\Actions\Action::make("export")
                    ->label("CSV Export")
                    ->action(function() {
                        $fileName = 'export_' . now()->format('Y-m-d_H-i-s') . '.csv';

                        return response()->streamDownload(function () {
                            $handle = fopen('php://output', 'w');

                            // Excel UTF-8 BOM
                            fwrite($handle, chr(0xEF).chr(0xBB).chr(0xBF));

                            $delimiter = ';';

                            $definition = collect($this->ownerRecord->form_definition)
                                ->pluck("data.name");

                            foreach ($startColumns = array_reverse(['ID', 'Reg Nr', 'Name', 'Approval', 'Reason']) as $item) {
                                $definition->prepend($item);
                            }

                            // heading
                            fputcsv($handle, $definition->toArray(), $delimiter);

                            foreach ($this->ownerRecord->sigFilledForms()->with("user")->get() as $filledForm) {
                                /** @var SigFilledForm $filledForm */
                                $entry = [
                                    $filledForm->id,
                                    $filledForm->user->reg_id,
                                    $filledForm->user->name,
                                    $filledForm->approval->name(),
                                    $filledForm->rejection_reason
                                ];

                                $data = collect($filledForm->form_data)->get('form_data', []);

                                // add correct host to file upload url
                                $formDefinition = collect($this->ownerRecord->form_definition ?: []);
                                foreach($data AS $dataEntry=>$value) {
                                    $type = $formDefinition->firstWhere("data.name", $dataEntry)['type'] ?? "";
                                    if($type == "file_upload")
                                        $data[$dataEntry] = Storage::disk("public")->url($value);
                                }

                                $entry = array_merge(
                                    $entry,
                                    $definition->slice(count($startColumns))->map(fn ($key) => data_get($data, $key))->toArray()
                                );

                                fputcsv($handle, $entry, $delimiter);
                            }

                            fclose($handle);
                        }, $fileName, [
                            'Content-Type'        => 'text/csv; charset=UTF-8',
                            'Cache-Control'       => 'no-store, no-cache, must-revalidate',
                            'Pragma'              => 'no-cache',
                            'Content-Disposition' => 'attachment; filename="'.$fileName.'"',
                        ]);
                    }),
            ])
            ->columns($this->getTableColumns())
            ->actions($this->getTableEntryActions())
            ->striped();
    }

    protected function getTableColumns(): array {
        $tableColumns = [
            Tables\Columns\TextColumn::make('id')
                ->sortable()
                ->label("ID"),
            Tables\Columns\IconColumn::make('approval')
                ->label('Approval')
                ->translateLabel(),
            Tables\Columns\TextColumn::make('rejection_reason'),
            Tables\Columns\TextColumn::make("user")
                ->formatStateUsing(FormHelper::formatStateUserWithRegId(true)),
            Tables\Columns\TextColumn::make('updated_at')
                ->sortable()
                ->since(),
        ];
        foreach ($this->ownerRecord->form_definition as $formDefinition) {
            $formData = $formDefinition['data'];
            switch ($formDefinition['type']) {
                case 'text':
                    $tableColumns[] = Tables\Columns\TextColumn::make($formData['name'])
                        ->limit()
                        ->label($this->getLabel($formData))
                        ->getStateUsing($this->getState($formData));
                    break;
                case 'textarea':
                    $tableColumns[] = Tables\Columns\TextColumn::make($formData['name'])
                        ->label($this->getLabel($formData))
                        ->limit()
                        ->getStateUsing($this->getState($formData))
                        ->listWithLineBreaks(true)
                        ->formatStateUsing(fn($state) => new HtmlString(nl2br(htmlspecialchars($state))));
                    break;
                case 'file_upload':
                    $tableColumns[] = Tables\Columns\ImageColumn::make($formData['name'])
                        ->label($this->getLabel($formData))
                        ->getStateUsing($this->getState($formData));
                    break;
                case 'checkbox':
                    $tableColumns[] = Tables\Columns\IconColumn::make($formData['name'])
                        ->boolean()
                        ->label($this->getLabel($formData))
                        ->getStateUsing($this->getState($formData));
                    break;
                case 'select':
                    $tableColumns[] = Tables\Columns\TextColumn::make($formData['name'])
                        ->label($this->getLabel($formData))
                        ->limit()
                        ->getStateUsing(function ($record) use ($formData) {
                            $options = $formData['options'];
                            foreach ($options as $option) {
                                $data = $option['data'] ?? [];
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

    private function getLabel($formData) {
        return App::getLocale() === 'en' ? $formData['label_en'] : $formData['label'] . ($formData['required'] ? ' *' : '');
    }

    private function getState($formData): \Closure {
        return function ($record) use ($formData) {
            return $record->form_data['form_data'][$formData['name']] ?? false;
        };
    }

    protected function getTableEntryActions(): array {
        $viewEntries = [];
        foreach ($this->ownerRecord->form_definition as $formDefinition) {
            $formData = $formDefinition['data'];
            switch ($formDefinition['type']) {
                case 'text':
                    $viewEntries[] = TextEntry::make($formData['name'])
                        ->inlineLabel()
                        ->label($this->getLabel($formData))
                        ->visible(fn($state) => $state)
                        ->getStateUsing($this->getState($formData));
                    break;
                case 'textarea':
                    $viewEntries[] = TextEntry::make($formData['name'])
                        ->label($this->getLabel($formData))
                        ->getStateUsing($this->getState($formData))
                        ->visible(fn($state) => $state)
                        ->listWithLineBreaks(true)
                        ->formatStateUsing(fn($state) => new HtmlString(nl2br(htmlspecialchars($state))));
                    break;
                case 'file_upload':
                    $viewEntries[] = ImageEntry::make($formData['name'])
                        ->label($this->getLabel($formData))
                        ->inlineLabel()
                        ->getStateUsing($this->getState($formData))
                        ->visible(fn($state) => $state)
                        ->url(function ($record) use ($formData) {
                            if ($record->form_data['form_data'][$formData['name']] ?? null) {
                                return Storage::url($record->form_data['form_data'][$formData['name']] ?? null);
                            }
                            return null;
                        }, true);
                    break;
                case 'checkbox':
                    $viewEntries[] = IconEntry::make($formData['name'])
                        ->boolean()
                        ->inlineLabel()
                        ->label($this->getLabel($formData))
                        ->getStateUsing($this->getState($formData));
                    break;
                case 'select':
                    $viewEntries[] = TextEntry::make($formData['name'])
                        ->label($this->getLabel($formData))
                        ->visible(fn($state) => $state)
                        ->getStateUsing(function ($record) use ($formData) {
                            $options = $formData['options'];
                            foreach ($options as $option) {
                                $data = $option['data'] ?? [];
                                if (($data['value'] ?? null) === ($record->form_data['form_data'][$formData['name']] ?? null)) {
                                    return App::getLocale() === 'en' ? $data['label_en'] : $data['label'];
                                }
                            }
                            return $record->form_data['form_data'][$formData['name']] ?? '';
                        });
                    break;
            }
        }
        return [
            Tables\Actions\ViewAction::make()
                ->modalHeading(fn($record) => $record->user->name)
                ->infolist($viewEntries)
                ->modalFooterActions([
                    Tables\Actions\Action::make("approve")
                        ->label(__("Approve"))
                        ->action(function (SigFilledForm $record) {
                            $record->approval = Approval::APPROVED;
                            $record->save();
                        })
                        ->requiresConfirmation()
                        ->visible(fn($record) => $record->approval == Approval::PENDING)
                        ->cancelParentActions(),
                    Tables\Actions\Action::make("reject")
                        ->label(__("Reject"))
                        ->form([
                            Forms\Components\TextInput::make("rejection_reason")
                        ])
                        ->color(Color::Red)
                        ->action(function (SigFilledForm $record, $data) {
                            $record->rejection_reason = data_get($data, "rejection_reason");
                            $record->approval = Approval::REJECTED;
                            $record->save();
                        })
                        ->visible(fn($record) => $record->approval == Approval::PENDING)
                        ->cancelParentActions()
                ]),
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
