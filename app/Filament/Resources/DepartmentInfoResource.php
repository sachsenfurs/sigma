<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DepartmentInfoResource\Pages;
use App\Models\DepartmentInfo;
use App\Models\SigEvent;
use App\Models\UserRole;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\Rules\Unique;

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
                self::getSigInfoFiled(),
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
                            ->label('SIG')
                            ->inlineLabel(),
                        TextEntry::make('userRole.title')
                            ->label('Department')
                            ->translateLabel()
                            ->inlineLabel(),
                        TextEntry::make('additional_info')
                            ->label('Requirements to Department')
                            ->listWithLineBreaks()
                            ->formatStateUsing(fn($state) => new HtmlString(nl2br(e($state))))
                            ->translateLabel(),
                        RepeatableEntry::make("sigEvent.timetableEntries")
                            ->label("Events")
                            ->translateLabel()
                            ->schema([
                                TextEntry::make("start")
                                    ->label("")
                                    ->dateTime(),
                                TextEntry::make("sigLocation.name_localized")
                                    ->label("")
                                    ->formatStateUsing(fn(Model $record) => $record->sigLocation->name_localized . " - " . $record->sigLocation->description_localized),

                            ]),
                    ]),
                Tables\Actions\EditAction::make()
                    ->label("Edit SIG")
                    ->translateLabel()
                    ->color(Color::Gray)
                    ->url(fn(Model $record) => SigEventResource::getUrl("edit", ['record' => $record->sigEvent])),
                Tables\Actions\EditAction::make()
                    ->modal()
                    ->url(null),
                Tables\Actions\DeleteAction::make(),
            ])
            ->recordUrl(null)
            ->filters([
                Tables\Filters\SelectFilter::make("userRole")
                    ->label("Department")
                    ->translateLabel()
                    ->searchable()
                    ->preload()
                    ->relationship("userRole", "title"),
            ])
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
            Tables\Columns\TextColumn::make('userRole.title')
                ->label('Department')
                ->translateLabel()
                ->sortable(),
            Tables\Columns\TextColumn::make('additional_info')
                ->label('Requirements to Department')
                ->translateLabel()
                ->sortable()
                ->limit(50),
        ];
    }

    private static function getSIGField(): Forms\Components\Component {
        return
            Forms\Components\Select::make('sig_event_id')
                ->label('Assigned SIG')
                ->translateLabel()
                ->live()
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
                ->unique(ignoreRecord: true, modifyRuleUsing: function(Unique $rule, Forms\Get $get) {
                    return $rule->where(function(Builder $query) use($get) {
                       return $query->where("sig_event_id", $get('sig_event_id'))
                           ->where("user_role_id", $get('user_role_id'));
                    });
                })
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

    private static function getSigInfoFiled() {
        return Forms\Components\Placeholder::make("additional_info")
            ->columnSpanFull()
            ->content(fn(Forms\Get $get) => new HtmlString(nl2br(e(SigEvent::find($get("sig_event_id"))?->additional_info))));
    }
}
