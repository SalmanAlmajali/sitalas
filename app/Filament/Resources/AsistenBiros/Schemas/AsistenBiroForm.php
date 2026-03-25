<?php

namespace App\Filament\Resources\AsistenBiros\Schemas;

use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class AsistenBiroForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Relasi Asisten & Biro')
                    ->description('Pilih hubungan antara asisten dan biro')
                    ->schema([

                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([

                            Select::make('asisten_unit_pengolah_id')
                                ->label('Asisten')
                                ->relationship(
                                    'asistenUnit',
                                    'direktorat',
                                    modifyQueryUsing: fn ($query) =>
                                        $query->where('asisten', true)
                                              ->where('active', true)
                                )
                                ->searchable()
                                ->preload()
                                ->placeholder('Pilih Asisten')
                                ->required(),

                            Select::make('biro_unit_pengolah_id')
                                ->label('Biro')
                                ->relationship(
                                    'biroUnit',
                                    'direktorat',
                                    modifyQueryUsing: fn ($query) =>
                                        $query->where('biro', true)
                                              ->where('active', true)
                                )
                                ->searchable()
                                ->preload()
                                ->placeholder('Pilih Biro')
                                ->required(),

                        ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}