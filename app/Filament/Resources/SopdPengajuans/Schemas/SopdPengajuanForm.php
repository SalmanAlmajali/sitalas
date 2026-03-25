<?php

namespace App\Filament\Resources\SopdPengajuans\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class SopdPengajuanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Grid::make([
                    'default' => 1,
                    'xl' => 2,
                ])
                ->columnSpanFull()
                ->schema([

                    Grid::make(1)->schema([

                        Section::make('Informasi Pengajuan')
                            ->description('Data utama pengajuan surat')
                            ->schema([
                                Grid::make(2)->schema([

                                    DatePicker::make('tanggal_surat')
                                        ->label('Tanggal Surat')
                                        ->required(),

                                    Select::make('sifat_surat_id')
                                        ->label('Sifat Surat')
                                        ->relationship('Sifat', 'sifat_surat')
                                        ->searchable()
                                        ->preload(),

                                    TextInput::make('kepada')
                                        ->label('Kepada')
                                        ->required()
                                        ->columnSpanFull(),

                                ]),
                            ])
                            ->extraAttributes(['class' => 'h-full']),
                    ]),

                    Grid::make(1)->schema([

                        Section::make('Detail & Kontak')
                            ->schema([
                                Grid::make(2)->schema([

                                    TextInput::make('kontak_person')
                                        ->label('No HP')
                                        ->tel()
                                        ->required(),

                                    FileUpload::make('upload_file')
                                        ->label('Upload File'),

                                ]),

                                Textarea::make('perihal')
                                    ->label('Perihal')
                                    ->rows(2)
                                    ->required()
                                    ->columnSpanFull(),

                                Textarea::make('keterangan')
                                    ->label('Catatan')
                                    ->rows(3)
                                    ->columnSpanFull(),
                            ])
                            ->extraAttributes(['class' => 'h-full']),
                    ]),
                ]),
            ]);
    }
}