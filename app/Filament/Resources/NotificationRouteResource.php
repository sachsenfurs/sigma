<?php

namespace App\Filament\Resources;

use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\MorphToSelect\Type;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\NotificationRouteResource\Pages\ListNotificationRoutes;
use App\Facades\NotificationService;
use App\Filament\Clusters\Settings;
use App\Filament\Helper\FormHelper;
use App\Filament\Resources\NotificationRouteResource\Pages;
use App\Filament\Traits\HasActiveIcon;
use App\Models\NotificationRoute;
use App\Models\Post\PostChannel;
use App\Models\User;
use App\Models\UserRole;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Gate;

class NotificationRouteResource extends Resource
{
    use HasActiveIcon;

    protected static ?string $model = NotificationRoute::class;
    protected static ?string $cluster = Settings::class;
    protected static ?int $navigationSort = 1500;
    protected static ?\Filament\Pages\Enums\SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-arrows-right-left';

    public static function getNavigationLabel(): string {
        return __("Notifications");
    }

    public static function getModelLabel(): string {
        return __("Notification");
    }

    public static function getPluralModelLabel(): string {
        return __("Notifications");
    }

    public static function canAccess(): bool {
        return Gate::allows("notificationSettings", \Spatie\LaravelSettings\Settings::class);
    }

    public static function form(Schema $schema): Schema {
        return $schema
            ->components([
                Select::make('notification')
                    ->label(__("Notification"))
                    ->required()
                    ->options(NotificationService::getRoutableNotificationNames())
                    ->hiddenOn("edit"),
                Select::make("channels")
                    ->label(__("Channels"))
                    ->options(collect(NotificationService::availableChannels())->mapWithKeys(fn($k, $v) => [$k => $k]))
                    ->required()
                    ->multiple(),
                MorphToSelect::make("notifiable")
                    ->label(__("Send Notification to"))
                    ->searchable()
                    ->preload()
                    ->columnSpanFull()
                    ->types([
                        Type::make(User::class)
                            ->label(__("User"))
                            ->titleAttribute("name")
                            ->getOptionLabelFromRecordUsing(FormHelper::formatUserWithRegId()),
                        Type::make(UserRole::class)
                            ->label(__("Department"))
                            ->titleAttribute("name")
                            ->getOptionLabelFromRecordUsing(fn($record) => $record->name_localized),
                        Type::make(PostChannel::class)
                            ->label(__("Channel"))
                            ->titleAttribute("name"),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('notification_name')
            ->columns([
                TextColumn::make("notifiable")
                    ->formatStateUsing(function($state) {
                        if($state instanceof User) {
                            return __("User") . " - " . $state->name;
                        }
                        if($state instanceof PostChannel) {
                            return __("Channel") . " - " . $state->name;
                        }
                        if($state instanceof UserRole) {
                            return __("Department") . " - " . $state->name_localized;
                        }
                    })
                    ->badge(),
                TextColumn::make("notification")
                    ->label(__("Notification"))
                    ->formatStateUsing(fn($record) => class_exists($record->notification) ? $record->notification::getName() : $record->notification),
                TextColumn::make("channels")
                    ->label(__("Channels"))
                    ->badge(),
            ])
            ->defaultGroup(
                Group::make("notification")
                    ->collapsible()
                    ->getTitleFromRecordUsing(fn($record) => class_exists($record->notification) ? $record->notification::getName() : $record->notification)
                    ->titlePrefixedWithLabel(false),
            )
            ->filters([
                SelectFilter::make("notification")
                    ->label(__("Notification"))
                    ->options(NotificationService::getRoutableNotificationNames()),
                SelectFilter::make("notifiable_type")
                    ->label(__("Notification Type"))
                    ->options([
                        'user' => __("User"),
                        'user_role' => __("Department"),
                        'post_channel' => __("Channel"),
                    ]),

            ], layout: FiltersLayout::AboveContent)
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => ListNotificationRoutes::route('/'),
        ];
    }
}
