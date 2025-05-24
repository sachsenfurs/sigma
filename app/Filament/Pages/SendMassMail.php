<?php

namespace App\Filament\Pages;

use App\Enums\MessageRecipient;
use App\Enums\Permission;
use App\Enums\PermissionLevel;
use App\Filament\Clusters\MessageCluster;
use App\Filament\Traits\HasActiveIcon;
use App\Models\User;
use App\Notifications\Messages\MessageNotification;
use App\Services\MessageRecipientService;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Pages\SubNavigationPosition;
use Illuminate\Contracts\Support\Htmlable;

class SendMassMail extends Page implements HasForms
{
    use InteractsWithForms, HasActiveIcon;

    public ?array $data = [];

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static string $view = 'filament.pages.send-mass-mail';

    protected static ?string $cluster = MessageCluster::class;
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
    protected static ?int $navigationSort = 10;

    public static function getNavigationLabel(): string {
        return __("Send Mass Mail");
    }

    public function getHeading(): string|Htmlable {
        return __("Send Mass Mail");
    }

    public static function canAccess(): bool {
        return auth()->user()?->hasPermission(Permission::MANAGE_CHATS, PermissionLevel::DELETE) ?? false;
    }

    protected function getFormSchema(): array {
        return [
            TextInput::make('subject')
                ->label(__("Subject"))
                ->required(),
            Textarea::make("body")
                ->label(__("Message Content"))
                ->required()
                ->autosize()
                ->helperText(__("Multiple languages can be defined with: {en}english text{/en}{de}deutscher text{/de}")),
            Select::make("recipients")
                ->label(__("Recipients"))
                ->options(MessageRecipient::strings())
                ->live()
                ->required(),
            Select::make("model")
                ->visible(function (Get $get) {
                    return collect(MessageRecipient::cases())->filter(fn($r) => $r->name == ($get("recipients") ?? ""))->first()?->targetClass();
                })
                ->options(function(Get $get) {
                    $recipientType = collect(MessageRecipient::cases())->filter(fn($r) => $r->name == ($get("recipients") ?? ""))->first();
                    if($class = $recipientType?->targetClass()) {
                        $items = $class::all();
                        if($items->count()) {
                            return $items->sortBy($recipientType->titleAttribute())->mapWithKeys(function($item) use ($recipientType) {
                                return [$item->id => $item->{$recipientType->titleAttribute()}];
                            });
                        }
                    }
                })
                ->required()
//                ->searchable(["id"]), // searchable won't work and will always return null. probably (another) filament bug?
        ];
    }


    protected function getFormStatePath(): string {
        return 'data';
    }

    public function submit(): void {
        $formData = $this->form->validate()['data'] ?? [];

        if($recipientType = collect(MessageRecipient::cases())->filter(fn($r) => $r->name == $formData["recipients"] ?? "")->first()) {
            $instance = null;
            if($recipientType->targetClass()) {
                $instance = $recipientType->targetClass()::find($formData["model"]);
            }
            $recipients = MessageRecipientService::getRecipients($recipientType, $instance);

            foreach($recipients as $recipient) {
                /**
                 * @var $recipient User
                 */
                $recipient->notify(new MessageNotification($formData['subject'], $formData['body']));
            }

            $this->reset();
            Notification::make("ok")
                ->body(__("Done"))
                ->success()
                ->send();
        }
    }

}
