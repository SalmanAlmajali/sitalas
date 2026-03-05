<?php

namespace App\Filament\Resources\SopdApproves\Pages;

use App\Filament\Resources\SopdApproves\SopdApproveResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSopdApproves extends ListRecords
{
    protected static string $resource = SopdApproveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
