<?php

namespace App\Filament\Resources\LostFoundItems;

use App\Enums\Permission;
use App\Enums\PermissionLevel;
use App\Filament\Resources\LostFoundItems\Pages\CreateLostFoundItem;
use App\Filament\Resources\LostFoundItems\Pages\ListLostFoundItems;
use App\Filament\Resources\LostFoundItems\Schemas\LostFoundItemForm;
use App\Filament\Resources\LostFoundItems\Tables\LostFoundItemsTable;
use App\Models\LostFoundItem;
use App\Settings\AppSettings;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class LostFoundItemResource extends Resource
{
    protected static ?string $model = LostFoundItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::MagnifyingGlassCircle;
    protected static ?int $navigationSort = 400;

    protected static ?string $recordTitleAttribute = 'title';

    public static function canAccess(): bool {
        return auth()->user()->hasPermission(Permission::MANAGE_ADMIN, PermissionLevel::WRITE) AND app(AppSettings::class)->lost_found_enabled;
    }

    /**
     * @return string|UnitEnum|null
     */
    public static function getNavigationGroup(): UnitEnum|string|null {
        return __("Lost and Found");
    }

    public static function getModelLabel(): string {
        return __("Lost & Found Item");
    }
    public static function getPluralModelLabel(): string {
        return __("Lost & Found Items");
    }

    public static function form(Schema $schema): Schema {
        return LostFoundItemForm::configure($schema);
    }

    public static function table(Table $table): Table {
        return LostFoundItemsTable::configure($table);
    }

    public static function getRelations(): array {
        return [
            //
        ];
    }

    public static function getPages(): array {
        return [
            'index' => ListLostFoundItems::route('/'),
            'create' => CreateLostFoundItem::route('/create'),
        ];
    }
}
