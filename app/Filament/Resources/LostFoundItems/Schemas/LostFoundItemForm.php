<?php

namespace App\Filament\Resources\LostFoundItems\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class LostFoundItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('lassie_id')
                    ->required()
                    ->numeric(),
                TextInput::make('image_url')
                    ->url()
                    ->default(null),
                TextInput::make('thumb_url')
                    ->url()
                    ->default(null),
                TextInput::make('title')
                    ->required(),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('status')
                    ->default(null),
                DateTimePicker::make('lost_at'),
                DateTimePicker::make('found_at'),
                DateTimePicker::make('returned_at'),
            ]);
    }
}
