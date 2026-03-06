<?php

namespace App\Filament\Resources\SopdPengajuans\Pages;

use App\Filament\Resources\SopdPengajuans\SopdPengajuanResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSopdPengajuan extends EditRecord
{
    protected static string $resource = SopdPengajuanResource::class;

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return ! $record->is_requested;
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
