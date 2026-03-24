<?php

namespace App\Filament\Resources\SifatSurats\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class SifatSuratForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Informasi Sifat Surat')
                    ->description('Masukkan jenis atau kategori sifat surat')
                    ->schema([
                        Grid::make(1)->schema([
                            TextInput::make('sifat_surat')
                                ->label('Sifat Surat')
                                ->placeholder('Contoh: Penting / Rahasia / Biasa')
                                ->required()
                                ->maxLength(100),
                        ]),
                    ]),
            ]);
    }
}