<?php

namespace App\Filament\Resources\TambahSuratKeluars\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use App\Models\KodeSurat;

class TambahSuratKeluarForm
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

                        Section::make('Informasi Surat')
                            ->description('Data utama surat keluar')
                            ->schema([
                                Grid::make(2)->schema([

                                    DatePicker::make('tanggal_surat')
                                        ->label('Tanggal Surat')
                                        ->required(),

                                    TextInput::make('no_urut')
                                        ->label('No Urut')
                                        ->numeric()
                                        ->required(),

                                    TextInput::make('no_surat')
                                        ->label('Nomor Surat')
                                        ->required()
                                        ->columnSpanFull(),
                                ]),
                            ])
                            ->extraAttributes(['class' => 'h-full']),

                        Section::make('Tujuan & Kontak')
                            ->schema([
                                Grid::make(2)->schema([

                                    TextInput::make('kepada')
                                        ->label('Kepada')
                                        ->required(),

                                    TextInput::make('kontak_person')
                                        ->label('Kontak Person')
                                        ->tel()
                                        ->required(),

                                    Select::make('direktorat_id')
                                        ->label('Unit Pengolah')
                                        ->relationship('unitPengolah', 'direktorat')
                                        ->searchable()
                                        ->preload()
                                        ->required()
                                        ->columnSpanFull(),
                                ]),
                            ])
                            ->extraAttributes(['class' => 'h-full']),
                    ]),

                    Grid::make(1)->schema([

                        Section::make('Klasifikasi & Detail')
                            ->schema([
                                Grid::make(2)->schema([

                                    Select::make('klasifikasi_id')
                                        ->label('Klasifikasi Surat')
                                        ->relationship('Klasifikasi', 'klasifikasi')
                                        ->searchable()
                                        ->preload()
                                        ->required()
                                        ->columnSpanFull(),

                                    Select::make('kode_id')
                                        ->label('Kode Surat')
                                        ->relationship('Kode', 'kode')
                                        ->getOptionLabelFromRecordUsing(fn (KodeSurat $record) =>
                                            $record->kode . ' - ' . $record->index
                                        ),

                                    Select::make('sifat_surat_id')
                                        ->label('Sifat Surat')
                                        ->relationship('Sifat', 'sifat_surat')
                                        ->searchable()
                                        ->preload()
                                        ->required(),
                                ]),

                                Textarea::make('perihal')
                                    ->label('Perihal')
                                    ->required()
                                    ->rows(2)
                                    ->columnSpanFull(),
                            ])
                            ->extraAttributes(['class' => 'h-full']),

                        Section::make('Lampiran & Keterangan')
                            ->schema([
                                Grid::make(2)->schema([

                                    FileUpload::make('upload_file')
                                        ->label('Upload File')
                                        ->required(),

                                    Textarea::make('lampiran')
                                        ->label('Lampiran')
                                        ->rows(2)
                                        ->required(),
                                ]),

                                Textarea::make('keterangan')
                                    ->label('Keterangan')
                                    ->rows(3)
                                    ->required()
                                    ->columnSpanFull(),
                            ])
                            ->extraAttributes(['class' => 'h-full']),
                    ]),
                ]),
            ]);
    }
}