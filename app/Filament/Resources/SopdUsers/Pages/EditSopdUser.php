<?php

namespace App\Filament\Resources\SopdUsers\Pages;

use App\Filament\Resources\SopdUsers\SopdUserResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSopdUser extends EditRecord
{
    protected static string $resource = SopdUserResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['sopd'] = true;

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
