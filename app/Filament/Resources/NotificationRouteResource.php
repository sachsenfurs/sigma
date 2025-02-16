<?php

namespace App\Filament\Resources;

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
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
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
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';

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

    public static function form(Form $form): Form {
        return $form
            ->schema([
                Forms\Components\Select::make('notification')
                    ->label(__("Notification"))
                    ->required()
                    ->options(NotificationService::getRoutableNotificationNames())
                    ->hiddenOn("edit"),
                Forms\Components\Select::make("channels")
                    ->label(__("Channels"))
                    ->options(collect(NotificationService::availableChannels())->mapWithKeys(fn($k, $v) => [$k => $k]))
                    ->required()
                    ->multiple(),
                Forms\Components\MorphToSelect::make("notifiable")
                    ->label(__("Send Notification to"))
                    ->searchable()
                    ->preload()
                    ->columnSpanFull()
                    ->types([
                        Forms\Components\MorphToSelect\Type::make(User::class)
                            ->label(__("User"))
                            ->titleAttribute("name")
                            ->getOptionLabelFromRecordUsing(FormHelper::formatUserWithRegId()),
                        Forms\Components\MorphToSelect\Type::make(UserRole::class)
                            ->label(__("Department"))
                            ->titleAttribute("name")
                            ->getOptionLabelFromRecordUsing(fn($record) => $record->name_localized),
                        Forms\Components\MorphToSelect\Type::make(PostChannel::class)
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
                Tables\Columns\TextColumn::make("notifiable")
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
                Tables\Columns\TextColumn::make("notification")
                    ->label(__("Notification"))
                    ->formatStateUsing(fn($record) => class_exists($record->notification) ? $record->notification::getName() : $record->notification),
                Tables\Columns\TextColumn::make("channels")
                    ->label(__("Channels"))
                    ->badge(),
            ])
            ->defaultGroup(
                Tables\Grouping\Group::make("notification")
                    ->collapsible()
                    ->getTitleFromRecordUsing(fn($record) => class_exists($record->notification) ? $record->notification::getName() : $record->notification)
                    ->titlePrefixedWithLabel(false),
            )
            ->filters([
                Tables\Filters\SelectFilter::make("notification")
                    ->label(__("Notification"))
                    ->options(NotificationService::getRoutableNotificationNames()),
                Tables\Filters\SelectFilter::make("notifiable_type")
                    ->label(__("Notification Type"))
                    ->options([
                        'user' => __("User"),
                        'user_role' => __("Department"),
                        'post_channel' => __("Channel"),
                    ]),

            ], layout: Tables\Enums\FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListNotificationRoutes::route('/'),
        ];
    }
}
