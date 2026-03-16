<?php

namespace App\Filament\Resources\ListBiros\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ListBiroForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('tanggal_terima')
                    ->label('Tanggal Terima')
                    ->nullable(),
                FileUpload::make('file_bukti_terima')
                    ->label('File Bukti Penerimaan')
                    ->nullable(),
            ]);
    }
}
