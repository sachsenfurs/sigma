<?php

namespace App\Filament\Pages;

use App\Settings\AppSettings;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Spatie\LaravelSettings\SettingsCasts\DateTimeInterfaceCast;

class Settings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $settings = AppSettings::class;

    protected static ?string $navigationGroup = "System";

    protected static ?int $navigationSort = 1999;

    public function form(Form $form): Form {
        return $form
            ->schema([
                Forms\Components\TextInput::make("event_name"),
                Forms\Components\DateTimePicker::make("event_start")
                    ->seconds(false)
                    ->dehydrateStateUsing(fn($state) => Carbon::parse($state))
//                Forms\Components\TextInput::make("event_start")
                ,

            ]);
    }
}
