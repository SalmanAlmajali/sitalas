<?php

namespace App\Filament\Resources\SopdUsers\Pages;

use App\Filament\Resources\SopdUsers\SopdUserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSopdUser extends CreateRecord
{
    protected static string $resource = SopdUserResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['sopd'] = true;

        return $data;
    }
}
