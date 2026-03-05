<?php

namespace App\Filament\Resources\SopdApproves\Pages;

use App\Filament\Resources\SopdApproves\SopdApproveResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSopdApprove extends ViewRecord
{
    protected static string $resource = SopdApproveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
