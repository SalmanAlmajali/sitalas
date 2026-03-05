<?php

namespace App\Filament\Resources\SopdApproves\Pages;

use App\Filament\Resources\SopdApproves\SopdApproveResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSopdApprove extends EditRecord
{
    protected static string $resource = SopdApproveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
