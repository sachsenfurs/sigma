<?php

namespace App\Filament\Resources\ChatResource\Pages;

use App\Filament\Resources\ChatResource;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Colors\Color;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class EditChat extends EditRecord
{
    protected static string $resource = ChatResource::class;

    public function getTitle(): string|Htmlable {
        return $this->record->user->name . " (#" . $this->record->user_id .  "): " . $this->record->subject;
    }

    public function getSubheading(): string|Htmlable|null {
        if($this->record->subjectable) {
            try {
                $url = Filament::getModelResource($this->record->subjectable::class)::getUrl("edit", ['record' => $this->record->subjectable]);
            } catch(RouteNotFoundException $e) {
                $url = "";
            }
            $name = Filament::getModelResource($this->record->subjectable::class)::getModelLabel() . " - ";
            $name .= $this->record->subjectable->name ?? $this->record->subjectable->title ?? "";
            return new HtmlString('<a href="' . $url . '">' . $name . '</a>');
        }
        return null;
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
                ->button(),
        ];
    }

    protected function getRedirectUrl(): ?string {
        return $this->previousUrl ?? self::getUrl("index");
    }

    public function form(Form $form): Form {
        return $form->schema([
            Section::make("Details")
                ->translateLabel()
                ->collapsed()
                ->schema(ChatResource::form($form)->getComponents())
        ]);
    }


}
