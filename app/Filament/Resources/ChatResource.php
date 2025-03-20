<?php

namespace App\Filament\Resources;

use App\Enums\ChatStatus;
use App\Filament\Helper\FormHelper;
use App\Filament\Resources\ChatResource\Pages;
use App\Filament\Resources\ChatResource\RelationManagers\ArtistsRelationManager;
use App\Filament\Resources\ChatResource\RelationManagers\ArtshowBidsRelationManager;
use App\Filament\Resources\ChatResource\RelationManagers\DealersRelationManager;
use App\Filament\Resources\ChatResource\RelationManagers\RoleRelationManager;
use App\Filament\Resources\ChatResource\RelationManagers\SigHostsRelationManager;
use App\Filament\Traits\HasActiveIcon;
use App\Models\Chat;
use App\Models\Ddas\ArtshowItem;
use App\Models\Ddas\Dealer;
use App\Models\Message;
use App\Models\SigEvent;
use App\Models\User;
use App\Models\UserRole;
use Carbon\Carbon;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\HtmlString;

class ChatResource extends Resource
{
    use HasActiveIcon;
    protected static ?string $model = Chat::class;
    protected static ?string $label = "Chat";

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';

    public static function getNavigationBadge(): ?string {
        return Cache::remember("filamentUnreadMessages".auth()->id(), 10, fn() => Message::unreadAdmin()->whereHas("chat", fn($query) => $query->involved())->count()) ?: null;
    }

    public static function getNavigationLabel(): string {
        return __("Messages");
    }

    public static function getNavigationGroup(): ?string {
        return __("Messages");
    }

    public static function form(Form $form): Form {
        $gridColumns = [
            Select::make("user_id")
                  ->relationship("user", "name")
                  ->getOptionLabelFromRecordUsing(FormHelper::formatUserWithRegId())
                  ->preload()
                  ->required()
                  ->searchable(["name", "reg_id"]),
            Select::make("user_role_id")
                  ->label("Department")
                  ->translateLabel()
                  ->relationship("userRole", "name")
                  ->required()
                  ->default(auth()->user()->roles->first()->id ?? null)
                  ->searchable()
                  ->preload()
                  ->getOptionLabelFromRecordUsing(fn($record) => $record->name_localized),
            Select::make("status")
                  ->required()
                  ->visibleOn("edit")
                  ->options(ChatStatus::class),
        ];

        return $form->schema([
            Grid::make()
                ->columns(count($gridColumns))
                ->schema($gridColumns),
            Fieldset::make()
                ->label("Subject")
                ->translateLabel()
                ->schema([
                    TextInput::make("subject")
                        ->maxLength(40)
                        ->required(),
                    MorphToSelect::make("subjectable")
                        ->label("Linked Subject")
                        ->translateLabel()
                        ->searchable()
                        ->preload()
                        ->types([
                            MorphToSelect\Type::make(SigEvent::class)
                                ->label(__("SIG"))
                                ->titleAttribute("name")
                                ->getOptionsUsing(function (Get $get) {
                                    return User::find($get("user_id"))?->sigHosts?->pluck("sigEvents")->flatten()->pluck("name", "id");
                                })
                                ->getOptionLabelFromRecordUsing(fn($record) => $record->name_localized),
                            MorphToSelect\Type::make(ArtshowItem::class)
                                ->getOptionsUsing(function (Get $get) {
                                    return User::find($get("user_id"))?->artists?->pluck("artshowItems")?->flatten()?->pluck("name", "id");
                                })
                                ->label(__("Art Show Item"))
                                ->titleAttribute("name"),
                            MorphToSelect\Type::make(Dealer::class)
                                ->getOptionsUsing(function (Get $get) {
                                    return User::find($get("user_id"))?->dealers?->pluck("name", "id");
                                })
                                ->label(__("Dealer"))
                                ->titleAttribute("name"),
                        ]),
                ]),
        ]);
    }

    public static function table(Table $table): Table {
        return $table
            ->modifyQueryUsing(fn(Builder $query) =>
                tap($query
                    ->with(["userRole", "messages.user"])
                    ->withCount(["messages" => function($query) {
                        return $query->unreadAdmin();
                    }])
                    ->withAggregate("messages", "created_at", "max")
                    ->orderByDesc("messages_max_created_at"),
                fn($query) => Gate::allows("deleteAny", Chat::class) ? $query : $query->involved()) // if admin show all, otherwise only involved
            )
            ->columns([
                TextColumn::make("status")
                    ->translateLabel()
                    ->badge(),
                TextColumn::make('userRole')
                    ->label("Department")
                    ->translateLabel()
                    ->formatStateUsing(fn($state) => $state->name_localized)
                    ->badge(),
                Tables\Columns\ImageColumn::make("user.avatar_thumb")
                    ->label("")
                    ->visibleFrom("md")
                    ->width("0"),
                TextColumn::make('user.name')
                    ->translateLabel()
                    ->searchable(),
                TextColumn::make("subject")
                    ->label("Subject")
                    ->translateLabel()
                    ->searchable(),
                TextColumn::make("messages_count")
                    ->label("")
                    ->width("0")
                    ->badge()
                    ->color(fn($state) => $state == 0 ? Color::Gray : Color::Red)
//                    ->getStateUsing(fn($record) => $record->unreadMessagesAdmin()->count() ?: null),
                    ->formatStateUsing(fn($state) => $state ?: null),
                TextColumn::make("last_message")
                    ->label("Last message")
                    ->translateLabel()
                    ->limit(120)
                    ->getStateUsing(function ($record) {
                        $msg = $record->messages->sortByDesc("created_at")->first();
                        if($msg)
                            return new HtmlString('<span style="word-break: break-word; text-wrap: wrap">'.e("{$msg->user->name}: {$msg->text}").'</span>');
                    })
                    ->visibleFrom("md"),
                TextColumn::make('messages_max_created_at')
                    ->label("")
                    ->formatStateUsing(fn($state) => Carbon::parse($state)->diffForHumans())
                    ->visibleFrom("xl"),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make("userRole")
                    ->relationship("userRole", "name")
                    ->getOptionLabelFromRecordUsing(fn($record) => $record->name_localized)
                    ->label("Department")
                    ->translateLabel()
                    ->searchable()
                    ->multiple()
                    ->preload()
//                    ->default(auth()->user()->roles->pluck("id")->toArray())
                ,
                Tables\Filters\SelectFilter::make("status")
                    ->options(ChatStatus::class)
                    ->default(),
            ])
            ->actions([
//                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make("status")
                        ->form([
                            Select::make("status")
                                ->options(ChatStatus::class)
                        ])
                        ->action(fn(Collection $records, array $data) => $records->each->update($data))
                        ->icon('heroicon-o-pencil-square')
                ]),
            ]);
    }


    public static function getRelations(): array {
        return [
            RoleRelationManager::class,
            ArtistsRelationManager::class,
            DealersRelationManager::class,
            SigHostsRelationManager::class,
            ArtshowBidsRelationManager::class,
        ];
    }


    public static function getPages(): array {
        return [
            'index' => Pages\ListChats::route('/'),
            'edit' => Pages\EditChat::route('/{record}')
        ];
    }

    /**
     * target models needs the "HasChats" trait
     */
    public static function getCreateChatAction(\Closure $default = null): Action {
        return Action::make("chat")
            ->label(fn($record) => $record->chats->count() > 0 ? __("Last Chat") : __("New Chat"))
            ->color(Color::Zinc)
            ->form([
                Select::make("user_id")
                    ->label(__("User"))
                    ->options(User::select(["id", "name", "reg_id"])->get()->keyBy("id")->map(fn($e) => $e->reg_id . " - ". $e->name))
                    ->getSearchResultsUsing(FormHelper::searchUserByNameAndRegId())
                    ->searchable()
                    ->required()
                    ->default($default),
                Select::make("user_role_id")
                    ->label(__("Department"))
                    ->relationship("chats.userRole", "name")
                    ->options(UserRole::chattable()->get()->pluck("name_localized", "id"))
                    ->searchable(['name', 'name_en'])
                    ->preload()
                    ->required(),
                TextInput::make("subject")
                    ->formatStateUsing(fn($record) => $record->name_localized ?? $record->name ?? "")
                    ->required(),
            ])
            ->action(function ($data, $record) {
                $chat = $record->chats()->create($data);
                redirect(ChatResource::getUrl('edit', ['record' => $chat]));
            })
            ->url(function(Model $record) {
                if($record->chats->count() > 0)
                   return (ChatResource::getUrl('edit', ['record' => $record->chats->first()]));
            });
    }
}
