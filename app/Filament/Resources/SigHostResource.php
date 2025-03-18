<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\SigManagement;
use App\Filament\Resources\SigHostResource\Pages;
use App\Filament\Resources\SigHostResource\RelationManagers;
use App\Filament\Traits\HasActiveIcon;
use App\Models\SigHost;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SigHostResource extends Resource
{
    use HasActiveIcon;
    protected static ?string $model = SigHost::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $cluster = SigManagement::class;
    protected static ?int $navigationSort = 20;
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function getPluralLabel(): ?string {
        return __('Hosts');
    }

    public static function form(Form $form): Form {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->columns(4)
                    ->schema([
                        self::getNameField()
                            ->columnSpan(2),
                        self::getRegIdField(),
                        self::getHideField(),
                    ]),
                self::getDescriptionField(),
                self::getDescriptionEnField(),
                self::getColorField(),
            ]);
    }

    public static function table(Table $table): Table {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query->with(["sigEvents" => fn($query) => $query->public(), "user", "sigEvents.timetableEntries"]))
            ->columns(self::getTableColumns())
            ->defaultSort('reg_id')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array {
        return [
            RelationManagers\SigEventsRelationManager::class,
        ];
    }

    public static function getPages(): array {
        return [
            'index' => Pages\ListSigHosts::route('/'),
            'create' => Pages\CreateSigHost::route('/create'),
            'view' => Pages\ViewSigHost::route('/{record}'),
            'edit' => Pages\EditSigHost::route('/{record}/edit'),
        ];
    }

    private static function getTableColumns(): array {
        return [
            Tables\Columns\ViewColumn::make('name')
                ->label('Name')
                ->translateLabel()
                ->searchable()
                ->sortable()
                ->view('filament.tables.columns.host-avatar'),
            Tables\Columns\TextColumn::make('reg_id')
                ->label('Reg Number')
                ->translateLabel()
                ->numeric()
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('publicSigEventCount')
                ->label('Event count')
                ->translateLabel()
                ->getStateUsing(fn (SigHost $record) => $record->public_sig_event_count),
            Tables\Columns\IconColumn::make('hide')
                ->label('Show Name on Schedule')
                ->translateLabel()
                ->getStateUsing(fn(SigHost $record) => !$record->hide)
                ->boolean()
                ->sortable(),
        ];
    }

    private static function getNameField(): Forms\Components\Component {
        return
            Forms\Components\TextInput::make('name')
                ->label('Name')
                ->translateLabel()
                ->required()->maxLength(255);
    }

    private static function getRegIdField(): Forms\Components\Component {
        return
            Forms\Components\TextInput::make('reg_id')
                ->label('Reg Number')
                ->translateLabel()
                ->numeric();
    }

    private static function getDescriptionField(): Forms\Components\Component {
        return
            Forms\Components\Textarea::make('description')
                ->label('Description')
                ->translateLabel()
                ->rows(5)
                ->maxLength(65535);
    }

    private static function getDescriptionEnField(): Forms\Components\Component {
        return
            Forms\Components\Textarea::make('description_en')
                ->label('Description (English)')
                ->translateLabel()
                ->rows(5)
                ->maxLength(65535);
    }

    private static function getHideField(): Forms\Components\Component {
        return
            Forms\Components\Toggle::make('hide')
                ->label('Hide name on schedule')
                ->inline(false)
                ->translateLabel();
    }
    private static function getColorField(): Forms\Components\Component {
        return
            Forms\Components\ColorPicker::make('color')
                ->label('Color in Schedule')
                ->default("#cccccc")
                ->formatStateUsing(fn(?Model $record) => $record?->color) // somehow the color isnt fetched from the db so we have to do it manually
                ->translateLabel()
                ->visible(fn(?Model $record) => self::can("delete")); // only allow admins to change color
    }
}
