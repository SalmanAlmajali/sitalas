<?php

namespace App\Filament\Resources\ListBiros;

use App\Filament\Resources\ListBiros\Pages\CreateListBiro;
use App\Filament\Resources\ListBiros\Pages\EditListBiro;
use App\Filament\Resources\ListBiros\Pages\ListListBiros;
use App\Filament\Resources\ListBiros\Pages\ViewListBiro;
use App\Filament\Resources\ListBiros\Schemas\ListBiroForm;
use App\Filament\Resources\ListBiros\Schemas\ListBiroInfolist;
use App\Filament\Resources\ListBiros\Tables\ListBirosTable;
use App\Models\ListBiro;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ListBiroResource extends Resource
{
    protected static ?string $model = ListBiro::class;
    protected static string | UnitEnum | null $navigationGroup= 'Surat Keluar';

    protected static ?string $navigationLabel = 'List Biro';


    #protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'list_biro';

    public static function form(Schema $schema): Schema
    {
        return ListBiroForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ListBiroInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ListBirosTable::configure($table);
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
            'index' => ListListBiros::route('/'),
            'create' => CreateListBiro::route('/create'),
            'view' => ViewListBiro::route('/{record}'),
            'edit' => EditListBiro::route('/{record}/edit'),
        ];
    }
}
