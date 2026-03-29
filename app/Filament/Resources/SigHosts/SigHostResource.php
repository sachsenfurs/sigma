<?php

namespace App\Filament\Resources\SigHosts;

use BackedEnum;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\SigHosts\RelationManagers\SigEventsRelationManager;
use App\Filament\Resources\SigHosts\Pages\ListSigHosts;
use App\Filament\Resources\SigHosts\Pages\CreateSigHost;
use App\Filament\Resources\SigHosts\Pages\ViewSigHost;
use App\Filament\Resources\SigHosts\Pages\EditSigHost;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Schemas\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ColorPicker;
use App\Filament\Clusters\SigManagement\SigManagementCluster;
use App\Filament\Traits\HasActiveIcon;
use App\Models\SigHost;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SigHostResource extends Resource
{
    use HasActiveIcon;
    protected static ?string $model = SigHost::class;
    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedUsers;
    protected static ?string $cluster = SigManagementCluster::class;
    protected static ?int $navigationSort = 20;
    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function getPluralLabel(): ?string {
        return __('Hosts');
    }

    public static function form(Schema $schema): Schema {
        return $schema
            ->components([
                Grid::make()
                    ->columns(4)
                    ->columnSpanFull()
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
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array {
        return [
            SigEventsRelationManager::class,
        ];
    }

    public static function getPages(): array {
        return [
            'index' => ListSigHosts::route('/'),
            'create' => CreateSigHost::route('/create'),
            'view' => ViewSigHost::route('/{record}'),
            'edit' => EditSigHost::route('/{record}/edit'),
        ];
    }

    private static function getTableColumns(): array {
        return [
            ViewColumn::make('name')
                ->label('Name')
                ->translateLabel()
                ->searchable()
                ->sortable()
                ->view('filament.tables.columns.host-avatar'),
            TextColumn::make('reg_id')
                ->label('Reg Number')
                ->translateLabel()
                ->numeric()
                ->searchable()
                ->sortable(),
            TextColumn::make('publicSigEventCount')
                ->label('Event count')
                ->translateLabel()
                ->getStateUsing(fn (SigHost $record) => $record->public_sig_event_count),
            IconColumn::make('hide')
                ->label('Show Name on Schedule')
                ->translateLabel()
                ->getStateUsing(fn(SigHost $record) => !$record->hide)
                ->boolean()
                ->sortable(),
        ];
    }

    private static function getNameField(): Component {
        return
            TextInput::make('name')
                ->label('Name')
                ->translateLabel()
                ->required()->maxLength(255);
    }

    private static function getRegIdField(): Component {
        return
            TextInput::make('reg_id')
                ->label('Reg Number')
                ->translateLabel()
                ->numeric();
    }

    private static function getDescriptionField(): Component {
        return
            Textarea::make('description')
                ->label('Description')
                ->translateLabel()
                ->rows(5)
                ->maxLength(65535);
    }

    private static function getDescriptionEnField(): Component {
        return
            Textarea::make('description_en')
                ->label('Description (English)')
                ->translateLabel()
                ->rows(5)
                ->maxLength(65535);
    }

    private static function getHideField(): Component {
        return
            Toggle::make('hide')
                ->label('Hide name on schedule')
                ->inline(false)
                ->translateLabel();
    }
    private static function getColorField(): Component {
        return
            ColorPicker::make('color')
                ->label('Color in Schedule')
                ->default("#cccccc")
                ->formatStateUsing(fn(?Model $record) => $record?->color) // somehow the color isnt fetched from the db so we have to do it manually
                ->translateLabel()
                ->visible(fn(?Model $record) => self::can("delete")); // only allow admins to change color
    }
}
