<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DepartmentInfoResource\Pages;
use App\Models\DepartmentInfo;
use App\Models\SigEvent;
use App\Models\UserRole;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class DepartmentInfoResource extends Resource
{
    protected static ?string $model = DepartmentInfo::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $navigationGroup = 'SIG';

    protected static ?int $navigationSort = 10;

    public static function getLabel(): ?string {
        return __('SIG Requirement');
    }

    public static function getPluralLabel(): ?string {
        return __('SIG Requirements');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                self::getSIGField(),
                self::getUserRoleField(),
                self::getAdditionalInfoField(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(self::getTableColumns())
            ->defaultGroup(
                Group::make('sigEvent.name')
                    ->label('')
                    ->collapsible()
                    ->getTitleFromRecordUsing(fn($record) => $record->sigEvent->name_localized)
            )
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label(false)
                    ->icon(false)
                    ->modalHeading(__('Requirements to Department'))
                    ->infolist([
                        TextEntry::make('sigEvent.name')
                            ->label('SIG'),
                        TextEntry::make('userRole.title')
                            ->label('Department')
                            ->translateLabel(),
                        TextEntry::make('additional_info')
                            ->label('Requirements to Department')
                            ->translateLabel(),
                    ]),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->recordUrl(null)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDepartmentInfos::route('/'),
            'create' => Pages\CreateDepartmentInfo::route('/create'),
            'edit' => Pages\EditDepartmentInfo::route('/{record}/edit'),
        ];
    }

    private static function getTableColumns(): array {
        return [
            Tables\Columns\TextColumn::make('sigEvent.name')
                ->label('SIG')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('userRole.title')
                ->label('Department')
                ->translateLabel()
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('additional_info')
                ->label('Requirements to Department')
                ->translateLabel()
                ->searchable()
                ->sortable()
                ->limit(50),
        ];
    }

    private static function getSIGField(): Forms\Components\Component {
        return
            Forms\Components\Select::make('sig_event_id')
                ->label('Assigned SIG')
                ->translateLabel()
                ->options(SigEvent::all()->pluck('name_localized', 'id'))
                ->searchable()
                ->required();
    }

    private static function getUserRoleField(): Forms\Components\Component {
        return
            Forms\Components\Select::make('user_role_id')
                ->label('Department')
                ->translateLabel()
                ->options(UserRole::all()->pluck('title', 'id'))
                ->searchable()
                ->required();
    }

    private static function getAdditionalInfoField(): Forms\Components\Component {
        return
            Forms\Components\Textarea::make('additional_info')
                ->label('Requirements to Department')
                ->translateLabel()
                ->required()
                ->rows(4)
                ->columnSpanFull();
    }
}
