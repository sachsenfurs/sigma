<?php

namespace App\Filament\Resources;

use App\Enums\ChatStatus;
use App\Filament\Resources\ChatResource\Pages;
use App\Filament\Resources\ChatResource\RelationManagers\ArtistsRelationManager;
use App\Filament\Resources\ChatResource\RelationManagers\ArtshowBidsRelationManager;
use App\Filament\Resources\ChatResource\RelationManagers\DealersRelationManager;
use App\Filament\Resources\ChatResource\RelationManagers\RoleRelationManager;
use App\Filament\Resources\ChatResource\RelationManagers\SigHostsRelationManager;
use App\Models\Chat;
use Carbon\Carbon;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class ChatResource extends Resource
{
    protected static ?string $model = Chat::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';

    public static function table(Table $table): Table {
        return $table
            ->modifyQueryUsing(fn($query) =>
                $query->involved()
                    ->with(["userRole", "messages.user"])
                    ->withCount(["messages" => function($query) {
                         return $query->to(auth()->user())->unread();
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
                    ->formatStateUsing(fn($state) => $state->title)
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
                    ->formatStateUsing(fn($state) => $state ?: null),
                TextColumn::make("last_message")
                    ->label("Last message")
                    ->translateLabel()
                    ->limit(120)
                    ->getStateUsing(function ($record) {
                        $msg = $record->messages->sortBy("created_at")->first();
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
                    ->relationship("userRole", "title")
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
