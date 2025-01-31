<?php

namespace App\Filament\Resources;

use App\Enums\ChatStatus;
use App\Filament\Resources\ChatResource\Pages;
use App\Filament\Resources\ChatResource\RelationManagers;
use App\Models\Chat;
use App\Settings\ChatSettings;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ChatResource extends Resource
{
    protected static ?string $model = Chat::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function canAccess(): bool {
        return app(ChatSettings::class)->enabled;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn($query) => $query->withAggregate("messages", "created_at", "max")->orderByDesc("messages_max_created_at"))
            ->columns([
                TextColumn::make("status")
                    ->translateLabel()
                    ->badge()
                    ->color(fn($state) => match($state) {
                        ChatStatus::OPEN => Color::Red,
                        ChatStatus::CLOSED => Color::Green,
                        default => Color::Gray,
                    }),
                Tables\Columns\ImageColumn::make("user.avatar_thumb")
                    ->label(""),
                TextColumn::make('user.name')
                    ->translateLabel(),
                TextColumn::make("subject")
                    ->translateLabel(),
                TextColumn::make('userRole')
                    ->label("Department")
                    ->translateLabel()
                    ->formatStateUsing(fn($state) => $state->title)
                    ->badge(),
                TextColumn::make('messages_max_created_at')
                    ->label("Last Message")
                    ->translateLabel()
                    ->formatStateUsing(fn($state) => Carbon::parse($state)->diffForHumans()),

            ])
            ->filters([
                //
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChats::route('/'),
            'create' => Pages\CreateChat::route('/create'),
            'view' => Pages\ViewChat::route('/{record}')
        ];
    }
}
