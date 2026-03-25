<?php

namespace App\Filament\Resources\IntruksiDisposisis\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class IntruksiDisposisiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Instruksi Disposisi')
                    ->description('Pengaturan instruksi dan tujuan disposisi')
                    ->schema([

                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([

                            Select::make('direktorat_id')
                                ->label('Direktorat')
                                ->relationship('unitPengolah', 'direktorat')
                                ->searchable()
                                ->preload()
                                ->placeholder('Pilih Direktorat')
                                ->required()
                                ->columnSpanFull(),

                            TextInput::make('intruksi')
                                ->label('Instruksi')
                                ->placeholder('Contoh: Segera diproses'),

                            TextInput::make('urutan')
                                ->label('Urutan')
                                ->numeric()
                                ->placeholder('0')
                                ->helperText('Digunakan untuk urutan prioritas'),

                            Toggle::make('active')
                                ->label('Status Aktif')
                                ->default(true)
                                ->inline(false),

                        ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}