<?php

namespace App\Filament\Resources\SopdPengajuans;

use App\Filament\Resources\SopdPengajuans\Pages\CreateSopdPengajuan;
use App\Filament\Resources\SopdPengajuans\Pages\EditSopdPengajuan;
use App\Filament\Resources\SopdPengajuans\Pages\ListSopdPengajuans;
use App\Filament\Resources\SopdPengajuans\Pages\ViewSopdPengajuan;
use App\Filament\Resources\SopdPengajuans\Schemas\SopdPengajuanForm;
use App\Filament\Resources\SopdPengajuans\Schemas\SopdPengajuanInfolist;
use App\Filament\Resources\SopdPengajuans\Tables\SopdPengajuansTable;
use App\Models\TambahSuratKeluar;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SopdPengajuanResource extends Resource
{
    protected static ?string $model = TambahSuratKeluar::class;

    protected static string | UnitEnum | null $navigationGroup= 'Surat Keluar';

    protected static ?string $navigationLabel = 'Sopd Pengajuan';

    protected static ?string $modelLabel = 'Sopd Pengajuan';
    protected static ?string $pluralModelLabel = 'Sopd Pengajuan';

    #protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'sopd_pengajuan';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('is_sopd_req', true)
            ->where('user_id', auth()->id());
    }

    public static function form(Schema $schema): Schema
    {
        return SopdPengajuanForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SopdPengajuanInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SopdPengajuansTable::configure($table);
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
            'index' => ListSopdPengajuans::route('/'),
            'create' => CreateSopdPengajuan::route('/create'),
            'view' => ViewSopdPengajuan::route('/{record}'),
            'edit' => EditSopdPengajuan::route('/{record}/edit'),
        ];
    }
}
