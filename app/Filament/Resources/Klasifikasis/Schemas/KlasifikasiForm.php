<?php

namespace App\Filament\Resources\Klasifikasis\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class KlasifikasiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Informasi Klasifikasi Surat')
                    ->description('Masukkan jenis klasifikasi surat')
                    ->schema([
                        Grid::make(1)->schema([
                            TextInput::make('klasifikasi')
                                ->label('Klasifikasi Surat')
                                ->placeholder('Contoh: Undangan / Laporan / Internal')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(100),
                        ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}