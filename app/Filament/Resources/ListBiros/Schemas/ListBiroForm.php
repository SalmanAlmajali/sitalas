<?php

namespace App\Filament\Resources\ListBiros\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class ListBiroForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Informasi Penerimaan')
                    ->description('Data penerimaan dokumen biro')
                    ->schema([

                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([

                            DatePicker::make('tanggal_terima')
                                ->label('Tanggal Terima')
                                ->nullable(),

                            FileUpload::make('file_bukti_terima')
                                ->label('File Bukti Penerimaan')
                                ->directory('bukti-terima')
                                ->nullable(),

                        ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}