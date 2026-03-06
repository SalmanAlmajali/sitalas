<?php

namespace App\Filament\Resources\SopdApproves;

use App\Filament\Resources\SopdApproves\Pages\CreateSopdApprove;
use App\Filament\Resources\SopdApproves\Pages\EditSopdApprove;
use App\Filament\Resources\SopdApproves\Pages\ListSopdApproves;
use App\Filament\Resources\SopdApproves\Pages\ViewSopdApprove;
use App\Filament\Resources\SopdApproves\Schemas\SopdApproveForm;
use App\Filament\Resources\SopdApproves\Schemas\SopdApproveInfolist;
use App\Filament\Resources\SopdApproves\Tables\SopdApprovesTable;
use App\Models\SopdApprove;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SopdApproveResource extends Resource
{
    protected static ?string $model = SopdApprove::class;

    protected static string | UnitEnum | null $navigationGroup= 'Surat Keluar';

    protected static ?string $navigationLabel = 'Sopd Approve';

    protected static ?string $modelLabel = 'Sopd Approve';
    protected static ?string $pluralModelLabel = 'Sopd Approve';

    #protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'sopd_approve';

    public static function form(Schema $schema): Schema
    {
        return SopdApproveForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SopdApproveInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SopdApprovesTable::configure($table);
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
            'index' => ListSopdApproves::route('/'),
            'create' => CreateSopdApprove::route('/create'),
            'view' => ViewSopdApprove::route('/{record}'),
            'edit' => EditSopdApprove::route('/{record}/edit'),
        ];
    }
}
