<?php

namespace App\Filament\Resources;

use App\Enums\ChatStatus;
use App\Filament\Resources\ChatResource\Pages;
use App\Filament\Resources\ChatResource\RelationManagers\ArtistsRelationManager;
use App\Filament\Resources\ChatResource\RelationManagers\ArtshowBidsRelationManager;
use App\Filament\Resources\ChatResource\RelationManagers\DealersRelationManager;
use App\Filament\Resources\ChatResource\RelationManagers\RoleRelationManager;
use App\Filament\Resources\ChatResource\RelationManagers\SigHostsRelationManager;
use App\Filament\Traits\HasActiveIcon;
use App\Models\Chat;
use App\Models\Message;
use Carbon\Carbon;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
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

    public static function table(Table $table): Table {
        return $table
            ->modifyQueryUsing(fn(Builder $query) =>
                $query
                    ->with(["userRole", "messages.user"])
                    ->withCount(["messages" => function($query) {
                        return $query->unreadAdmin();
                    }])
                    ->withAggregate("messages", "created_at", "max")
                    ->orderByDesc("messages_max_created_at")
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChats::route('/'),
            'edit' => Pages\EditChat::route('/{record}')
        ];
    }
}
