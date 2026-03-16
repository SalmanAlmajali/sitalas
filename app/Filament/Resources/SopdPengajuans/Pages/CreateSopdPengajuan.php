<?php

namespace App\Filament\Resources\SopdPengajuans\Pages;

use App\Filament\Resources\SopdPengajuans\SopdPengajuanResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateSopdPengajuan extends CreateRecord
{
    protected static string $resource = SopdPengajuanResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id();   // menyimpan user yang login
        $data['is_sopd_req'] = true;     // menandai data berasal dari SOPD

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}