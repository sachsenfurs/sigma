<?php

namespace App\Filament\Resources\ChatResource\Pages;

use App\Enums\ChatStatus;
use App\Filament\Resources\ChatResource;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Colors\Color;
use Illuminate\Contracts\Support\Htmlable;

class EditChat extends EditRecord
{
    protected static string $resource = ChatResource::class;

    public function getTitle(): string|Htmlable {
        return $this->record->user->name . " (#" . $this->record->user_id .  "): " . $this->record->subject;
    }

    protected function getHeaderWidgets(): array {
        return [
            ChatResource\Widgets\Chatbox::class,
        ];
    }

    protected function getHeaderActions(): array {
        return [
            Action::make("markAsRead")
                ->label("Mark as read")
                ->translateLabel()
                ->color(Color::Green)
                ->action(function ($record) {
                    $record->markAsRead();
                    $this->dispatch("refresh");
                })
                ->button()
            ,
        ];
    }

    public function form(Form $form): Form {
        return $form
            ->schema([
                TextInput::make("subject")
                    ->label("Subject")
                    ->translateLabel(),
                Select::make("user_role_id")
                    ->label("Department")
                    ->translateLabel()
                    ->relationship("userRole", "title")
                    ->searchable()
                    ->preload(),
                Select::make("status")
                    ->required()
                    ->options(ChatStatus::class),
            ])
            ->columns(3);
    }


}
